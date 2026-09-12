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
        $query = Planta::with(['categoria', 'subtema', 'categorias']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq
                ->where('nombre',    'ilike', "%{$q}%")
                ->orWhere('cientifico', 'ilike', "%{$q}%"));
        }

        $plantas    = $query->orderBy('nombre')->paginate(20);
        $categorias = Categoria::orderBy('nombre')->get();

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
            'nombre'               => 'required|string|max:150',
            'cientifico'           => 'nullable|string|max:150',
            'categoria_id'         => 'required|exists:categorias,id',
            'categorias_extra'     => 'nullable|array',
            'categorias_extra.*'   => 'exists:categorias,id',
            'subtema_id'           => 'required|exists:subtemas,id',
            'uso'                  => 'required|string|max:255',
            'instrucciones'        => 'required|string',
            'contexto'             => 'nullable|string',
            'relato'               => 'nullable|string',
            'video_url'            => 'nullable|string|max:500',
            'video_file'           => 'nullable|file|mimes:mp4,webm,ogv,avi|max:51200',
            'video_persona_nombre' => 'nullable|string|max:150',
            'video_persona_rol'    => 'nullable|string|max:100',
            'video_validado'       => 'boolean',
            'imagen'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'tags'                 => 'nullable|string|max:300',
            'verificada'           => 'boolean',
        ]);

        $data['cientifico']           = $data['cientifico'] ?? 'sp.';
        $data['verificada']           = $request->boolean('verificada');
        $data['video_validado']       = $request->boolean('video_validado');
        $data['video_url']            = $data['video_url'] ?: null;
        $data['video_persona_nombre'] = $data['video_persona_nombre'] ?? null;
        $data['video_persona_rol']    = $data['video_persona_rol'] ?? null;
        $data['tags']                 = $data['tags'] ?? '';

        if ($data['video_url'] && preg_match('/(youtube\.com|youtu\.be)/', $data['video_url'])) {
            $videoId = $this->extractYouTubeVideoId($data['video_url']);
            if ($videoId) {
                $data['video_url'] = "https://www.youtube.com/embed/{$videoId}";
            }
        }

        if ($data['video_url'] && !preg_match('/^(https?:\/\/|videos\/)/', $data['video_url'])) {
            return back()->withErrors(['video_url' => 'La URL del video debe ser una URL externa válida.'])->withInput();
        }

        if ($request->hasFile('video_file')) {
            $data['video_url'] = \App\Services\CloudinaryUploader::upload(
                $request->file('video_file'), 'video', 'etnobotanica/videos'
            );
        }

        if ($request->hasFile('imagen')) {
            $data['img_url']  = \App\Services\CloudinaryUploader::upload(
                $request->file('imagen'), 'image', 'etnobotanica/plantas'
            );
            $data['img_path'] = null;
        }

        $categoriasExtra = $data['categorias_extra'] ?? [];
        unset($data['imagen'], $data['categorias_extra']);

        $planta = Planta::create($data);

        // Sincronizar categorías M2M (incluye la principal)
        $todasCategorias = array_unique(array_merge([$data['categoria_id']], $categoriasExtra));
        $planta->categorias()->sync($todasCategorias);

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
        $planta->load('categorias');
        return view('admin.plantas.edit', compact('planta', 'categorias'));
    }

    public function update(Request $request, Planta $planta)
    {
        $data = $request->validate([
            'nombre'               => 'required|string|max:150',
            'cientifico'           => 'nullable|string|max:150',
            'categoria_id'         => 'required|exists:categorias,id',
            'categorias_extra'     => 'nullable|array',
            'categorias_extra.*'   => 'exists:categorias,id',
            'subtema_id'           => 'required|exists:subtemas,id',
            'uso'                  => 'required|string|max:255',
            'instrucciones'        => 'required|string',
            'contexto'             => 'nullable|string',
            'relato'               => 'nullable|string',
            'video_url'            => 'nullable|string|max:500',
            'video_file'           => 'nullable|file|mimes:mp4,webm,ogv,avi|max:51200',
            'borrar_video'         => 'nullable|boolean',
            'video_persona_nombre' => 'nullable|string|max:150',
            'video_persona_rol'    => 'nullable|string|max:100',
            'video_validado'       => 'boolean',
            'imagen'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'borrar_imagen'        => 'nullable|boolean',
            'tags'                 => 'nullable|string|max:300',
            'verificada'           => 'boolean',
        ]);

        $data['verificada']           = $request->boolean('verificada');
        $data['video_validado']       = $request->boolean('video_validado');
        $data['video_url']            = $data['video_url'] ?: null;
        $data['video_persona_nombre'] = $data['video_persona_nombre'] ?? null;
        $data['video_persona_rol']    = $data['video_persona_rol'] ?? null;
        $data['tags']                 = $data['tags'] ?? '';

        if ($data['video_url'] && preg_match('/(youtube\.com|youtu\.be)/', $data['video_url'])) {
            $videoId = $this->extractYouTubeVideoId($data['video_url']);
            if ($videoId) {
                $data['video_url'] = "https://www.youtube.com/embed/{$videoId}";
            }
        }

        if ($data['video_url'] && !preg_match('/^(https?:\/\/|videos\/)/', $data['video_url'])) {
            return back()->withErrors(['video_url' => 'La URL del video debe ser una URL externa válida.'])->withInput();
        }

        if ($request->boolean('borrar_video')) {
            if ($planta->video_url && !str_starts_with($planta->video_url, 'http')) {
                Storage::disk('public')->delete($planta->video_url);
            }
            $data['video_url'] = null;
        } elseif ($request->hasFile('video_file')) {
            if ($planta->video_url && !str_starts_with($planta->video_url, 'http')) {
                Storage::disk('public')->delete($planta->video_url);
            }
            $data['video_url'] = \App\Services\CloudinaryUploader::upload(
                $request->file('video_file'), 'video', 'etnobotanica/videos'
            );
        }

        if ($request->boolean('borrar_imagen') || $request->hasFile('imagen')) {
            if ($planta->img_path) {
                Storage::disk('public')->delete($planta->img_path);
            }
            $data['img_path'] = null;
            $data['img_url']  = null;
        }

        if ($request->hasFile('imagen')) {
            $data['img_url'] = \App\Services\CloudinaryUploader::upload(
                $request->file('imagen'), 'image', 'etnobotanica/plantas'
            );
        }

        $categoriasExtra = $data['categorias_extra'] ?? [];
        unset($data['imagen'], $data['borrar_imagen'], $data['categorias_extra']);

        $planta->update($data);

        // Sincronizar categorías M2M
        $todasCategorias = array_unique(array_merge([$data['categoria_id']], $categoriasExtra));
        $planta->categorias()->sync($todasCategorias);

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
        $pattern = '/(?:youtube\.com\/watch\?v=|youtube\.com\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}