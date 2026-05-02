<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditoriaLog;
use App\Models\Categoria;
use App\Models\Planta;
use App\Models\Subtema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantaAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Planta::with(['categoria', 'subtema']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq
                ->where('nombre',    'ilike', "%{$q}%")
                ->orWhere('cientifico', 'ilike', "%{$q}%"));
        }

        $plantas     = $query->orderBy('nombre')->paginate(20);
        $categorias  = Categoria::orderBy('nombre')->get();

        return view('admin.plantas.index', compact('plantas', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::with('subtemas')->orderBy('nombre')->get();
        return view('admin.plantas.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'        => 'required|string|max:150',
            'cientifico'    => 'nullable|string|max:150',
            'categoria_id'  => 'required|exists:categorias,id',
            'subtema_id'    => 'required|exists:subtemas,id',
            'uso'           => 'required|string|max:255',
            'instrucciones' => 'required|string',
            'contexto'      => 'nullable|string',
            'relato'        => 'nullable|string',
            'video_url'     => 'nullable|string|max:500',
            'video_file'    => 'nullable|file|mimes:mp4,webm,ogv,avi|max:51200',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'tags'          => 'nullable|string|max:300',
            'verificada'    => 'boolean',
        ]);

        $data['cientifico'] = $data['cientifico'] ?? 'sp.';
        $data['verificada'] = $request->boolean('verificada');
        $data['video_url']  = $data['video_url'] ?: null;

        // Convertir URLs de YouTube watch a embed si es necesario
        if ($data['video_url'] && str_contains($data['video_url'], 'youtube.com/watch?v=')) {
            $videoId = $this->extractYouTubeVideoId($data['video_url']);
            if ($videoId) {
                $data['video_url'] = "https://www.youtube.com/embed/{$videoId}";
            }
        }

        // Validar formato del video_url
        if ($data['video_url'] && !preg_match('/^(https?:\/\/|videos\/)/', $data['video_url'])) {
            return back()->withErrors(['video_url' => 'La URL del video debe ser una URL externa válida o una ruta local que comience con "videos/".'])->withInput();
        }

        // Procesar archivo de video si se subió
        if ($request->hasFile('video_file')) {
            $data['video_url'] = $request->file('video_file')->store('videos', 'public');
        }

        if ($request->hasFile('imagen')) {
            $data['img_path'] = $request->file('imagen')->store('plantas', 'public');
        }

        unset($data['imagen']);

        $planta = Planta::create($data);

        AuditoriaLog::create([
            'accion'     => 'CREÓ',
            'elemento'   => $planta->nombre,
            'usuario_id' => auth()->id(),
        ]);

        return redirect()->route('admin.plantas.index')
            ->with('success', "Planta \"{$planta->nombre}\" creada.");
    }

    public function edit(Planta $planta)
    {
        $categorias = Categoria::with('subtemas')->orderBy('nombre')->get();
        return view('admin.plantas.edit', compact('planta', 'categorias'));
    }

    public function update(Request $request, Planta $planta)
    {
        $data = $request->validate([
            'nombre'        => 'required|string|max:150',
            'cientifico'    => 'nullable|string|max:150',
            'categoria_id'  => 'required|exists:categorias,id',
            'subtema_id'    => 'required|exists:subtemas,id',
            'uso'           => 'required|string|max:255',
            'instrucciones' => 'required|string',
            'contexto'      => 'nullable|string',
            'relato'        => 'nullable|string',
            'video_url'     => 'nullable|string|max:500',
            'video_file'    => 'nullable|file|mimes:mp4,webm,ogv,avi|max:51200',
            'borrar_video'  => 'nullable|boolean',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'borrar_imagen' => 'nullable|boolean',
            'tags'          => 'nullable|string|max:300',
            'verificada'    => 'boolean',
        ]);

        $data['verificada'] = $request->boolean('verificada');
        $data['video_url']  = $data['video_url'] ?: null;

        // Convertir URLs de YouTube watch a embed si es necesario
        if ($data['video_url'] && str_contains($data['video_url'], 'youtube.com/watch?v=')) {
            $videoId = $this->extractYouTubeVideoId($data['video_url']);
            if ($videoId) {
                $data['video_url'] = "https://www.youtube.com/embed/{$videoId}";
            }
        }

        // Validar formato del video_url
        if ($data['video_url'] && !preg_match('/^(https?:\/\/|videos\/)/', $data['video_url'])) {
            return back()->withErrors(['video_url' => 'La URL del video debe ser una URL externa válida o una ruta local que comience con "videos/".'])->withInput();
        }

        // Procesar video: borrar existente, subir nuevo, o mantener actual
        if ($request->boolean('borrar_video')) {
            if ($planta->video_url && !str_starts_with($planta->video_url, 'http')) {
                Storage::disk('public')->delete($planta->video_url);
            }
            $data['video_url'] = null;
        } elseif ($request->hasFile('video_file')) {
            // Borrar video anterior si existe y no es URL externa
            if ($planta->video_url && !str_starts_with($planta->video_url, 'http')) {
                Storage::disk('public')->delete($planta->video_url);
            }
            $data['video_url'] = $request->file('video_file')->store('videos', 'public');
        }

        // Borrar imagen existente si se pidió o se sube una nueva
        if ($request->boolean('borrar_imagen') || $request->hasFile('imagen')) {
            if ($planta->img_path) {
                Storage::disk('public')->delete($planta->img_path);
            }
            $data['img_path'] = null;
        }

        if ($request->hasFile('imagen')) {
            $data['img_path'] = $request->file('imagen')->store('plantas', 'public');
        }

        unset($data['imagen'], $data['borrar_imagen']);

        $planta->update($data);

        AuditoriaLog::create([
            'accion'     => 'EDITÓ',
            'elemento'   => $planta->nombre,
            'usuario_id' => auth()->id(),
        ]);

        return redirect()->route('admin.plantas.index')
            ->with('success', "Planta \"{$planta->nombre}\" actualizada.");
    }

    public function destroy(Planta $planta)
    {
        $nombre = $planta->nombre;

        if ($planta->img_path) {
            Storage::disk('public')->delete($planta->img_path);
        }

        $planta->delete();

        AuditoriaLog::create([
            'accion'     => 'ELIMINÓ',
            'elemento'   => $nombre,
            'usuario_id' => auth()->id(),
        ]);

        return redirect()->route('admin.plantas.index')
            ->with('success', "Planta \"{$nombre}\" eliminada.");
    }

    public function subtemasPorCategoria(Categoria $categoria)
    {
        return response()->json($categoria->subtemas()->select('id', 'nombre')->get());
    }

    private function extractYouTubeVideoId($url)
    {
        $pattern = '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
