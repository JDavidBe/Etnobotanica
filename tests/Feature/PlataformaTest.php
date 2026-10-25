<?php

/*
|--------------------------------------------------------------------------
| Pruebas funcionales de la plataforma Etnobotánica (fase 2)
|--------------------------------------------------------------------------
|
| Cubren los casos de uso centrales documentados en la monografía:
| catálogo y fichas (RF-01, RF-03), búsqueda por síntoma y sinónimo
| (RF-02), advertencia de seguridad (RF-12), aportes con consentimiento
| (RF-07), moderación con auditoría y notificación (RF-07, RF-09),
| control de acceso por rol (RNF-07) y Dialogando Saberes (RF-10).
|
*/

use App\Models\Aporte;
use App\Models\AuditoriaLog;
use App\Models\Categoria;
use App\Models\Notificacion;
use App\Models\Planta;
use App\Models\Subtema;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->medicina = Categoria::create(['nombre' => 'Medicina', 'icono' => 'fas fa-briefcase-medical', 'descripcion' => 'Saberes curativos locales', 'orden' => 1]);
    $this->alimentacion = Categoria::create(['nombre' => 'Alimentación', 'icono' => 'fas fa-seedling', 'descripcion' => 'Nutrición', 'orden' => 2]);
    $this->digestion = Subtema::create(['categoria_id' => $this->medicina->id, 'nombre' => 'Digestión']);
    $this->humana = Subtema::create(['categoria_id' => $this->alimentacion->id, 'nombre' => 'Personas']);
});

function crearPlanta(array $datos): Planta
{
    return Planta::create(array_merge([
        'cientifico'    => 'sp.',
        'uso'           => 'Uso tradicional',
        'instrucciones' => 'Preparar en infusión.',
        'tags'          => '',
        'verificada'    => true,
    ], $datos));
}

function usuarioConRol(string $rol): User
{
    static $n = 0;
    $n++;
    $u = User::create(['name' => "Usuario {$n}", 'email' => "usuario{$n}@prueba.test", 'password' => bcrypt('secreto-de-prueba'), 'activo' => true]);
    $u->assignRole($rol);
    return $u;
}

it('PF-01 muestra la página de inicio con las categorías', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Medicina')
        ->assertSee('Alimentación');
});

it('PF-02 muestra la ficha con su nombre científico y la etiqueta de origen', function () {
    $p = crearPlanta(['nombre' => 'Manzanilla', 'cientifico' => 'Matricaria chamomilla', 'categoria_id' => $this->medicina->id, 'subtema_id' => $this->digestion->id]);

    $this->get("/plantas/{$p->id}")
        ->assertOk()
        ->assertSee('Manzanilla')
        ->assertSee('Matricaria chamomilla')
        ->assertSee('Contenido del experto');
});

it('PF-03 encuentra plantas por síntoma usando sinónimos', function () {
    crearPlanta(['nombre' => 'Eucalipto', 'uso' => 'Alivio respiratorio y de la tos', 'categoria_id' => $this->medicina->id, 'subtema_id' => $this->digestion->id]);

    $this->get('/buscar?q=gripa')
        ->assertOk()
        ->assertSee('Eucalipto');
});

it('PF-04 no consulta con menos de dos caracteres', function () {
    crearPlanta(['nombre' => 'Ruda', 'categoria_id' => $this->medicina->id, 'subtema_id' => $this->digestion->id]);

    $this->get('/buscar?q=r')
        ->assertOk()
        ->assertDontSee('Ruda');
});

it('PF-05 muestra el aviso de uso responsable en las fichas medicinales', function () {
    $p = crearPlanta(['nombre' => 'Manzanilla', 'cientifico' => 'Matricaria chamomilla', 'categoria_id' => $this->medicina->id, 'subtema_id' => $this->digestion->id]);

    $this->get("/plantas/{$p->id}")
        ->assertOk()
        ->assertSee('Uso responsable')
        ->assertSee('no es una recomendación médica');
});

it('PF-06 muestra la advertencia reforzada en especies de riesgo', function () {
    $p = crearPlanta(['nombre' => 'Borrachero', 'cientifico' => 'Brugmansia sp.', 'categoria_id' => $this->medicina->id, 'subtema_id' => $this->digestion->id]);

    $this->get("/plantas/{$p->id}")
        ->assertOk()
        ->assertSee('Precaución con esta planta')
        ->assertSee('altamente tóxica');
});

it('PF-07 no muestra la advertencia en fichas no medicinales sin riesgo', function () {
    $p = crearPlanta(['nombre' => 'Mazorca', 'cientifico' => 'Zea mays', 'categoria_id' => $this->alimentacion->id, 'subtema_id' => $this->humana->id]);

    $this->get("/plantas/{$p->id}")
        ->assertOk()
        ->assertDontSee('Uso responsable')
        ->assertDontSee('Precaución con esta planta');
});

it('PF-08 exige iniciar sesión para aportar', function () {
    $this->get('/aportar')->assertRedirect('/login');
});

it('PF-09 impide el acceso al panel de administración sin rol', function () {
    $this->actingAs(usuarioConRol('lector'))
        ->get('/admin')
        ->assertForbidden();
});

it('PF-10 rechaza un aporte sin consentimiento informado', function () {
    $this->actingAs(usuarioConRol('lector'))
        ->post('/aportar', [
            'nombre_planta' => 'Arrayán',
            'categoria'     => 'Cultura',
            'uso'           => 'Ornamental',
            'preparacion'   => 'Frutos maduros',
        ])
        ->assertSessionHasErrors(['consentimiento', 'consentimiento_img']);

    expect(Aporte::count())->toBe(0);
});

it('PF-11 guarda el aporte como pendiente y notifica a los moderadores', function () {
    $moderador = usuarioConRol('moderador');

    $this->actingAs(usuarioConRol('lector'))
        ->post('/aportar', [
            'nombre_planta'      => 'Arrayán',
            'categoria'          => 'Cultura',
            'uso'                => 'Ornamental y comestible',
            'preparacion'        => 'Frutos maduros como alimento',
            'relato'             => 'Lo aprendí de las personas mayores.',
            'consentimiento'     => '1',
            'consentimiento_img' => '1',
        ])
        ->assertSessionHasNoErrors();

    $aporte = Aporte::first();
    expect($aporte->estado)->toBe('pendiente');
    expect(Notificacion::where('user_id', $moderador->id)->count())->toBe(1);

    // Un aporte pendiente no es visible al público
    $this->get("/aportes/{$aporte->id}")->assertNotFound();
});

it('PF-12 aprueba un aporte, lo registra en la auditoría y notifica a su autor', function () {
    $autor = usuarioConRol('lector');
    $aporte = Aporte::create(['nombre_planta' => 'Arrayán', 'cientifico' => '', 'categoria' => 'Cultura', 'uso' => 'Ornamental', 'preparacion' => 'Frutos', 'estado' => 'pendiente', 'enviado_por' => $autor->name, 'ip_origen' => '127.0.0.1', 'user_id' => $autor->id]);

    $this->actingAs(usuarioConRol('moderador'))
        ->patch("/admin/moderacion/{$aporte->id}/aprobar");

    expect($aporte->fresh()->estado)->toBe('aprobado');
    expect(AuditoriaLog::where('accion', 'PUBLICÓ')->count())->toBe(1);
    expect(Notificacion::where('user_id', $autor->id)->where('tipo', 'aporte_aprobado')->count())->toBe(1);

    $this->get("/aportes/{$aporte->id}")->assertOk()->assertSee('Arrayán');
});

it('PF-13 exige un motivo para rechazar un aporte', function () {
    $aporte = Aporte::create(['nombre_planta' => 'Prueba', 'cientifico' => '', 'categoria' => 'Cultura', 'uso' => 'x', 'preparacion' => 'x', 'estado' => 'pendiente', 'enviado_por' => 'Anónimo', 'ip_origen' => '127.0.0.1']);

    $this->actingAs(usuarioConRol('moderador'))
        ->post("/admin/moderacion/{$aporte->id}/rechazar", [])
        ->assertSessionHasErrors('motivo_rechazo');

    expect($aporte->fresh()->estado)->toBe('pendiente');
});

it('PF-14 muestra la sección Dialogando Saberes', function () {
    $this->get('/dialogando-saberes')
        ->assertOk()
        ->assertSee('Dialogando Saberes');
});

it('PF-15 ya no expone la ruta pública de ejecución de seeders', function () {
    $this->get('/run-sumapaz-seeder-xyz789')->assertNotFound();
});

it('PF-16 una ficha del catálogo hereda la taxonomía de la Enciclopedia', function () {
    $enc = Categoria::create(['nombre' => 'Enciclopedia Botánica', 'icono' => 'fas fa-book-open', 'descripcion' => 'x', 'orden' => 4]);
    $cat = Subtema::create(['categoria_id' => $enc->id, 'nombre' => 'Catálogo Taxonómico']);
    crearPlanta(['nombre' => 'Ruda', 'cientifico' => 'Ruta graveolens', 'categoria_id' => $enc->id, 'subtema_id' => $cat->id,
        'reino' => 'Plantae', 'division' => 'Magnoliophyta', 'clase' => 'Magnoliopsida', 'orden' => 'Sapindales', 'familia' => 'Rutaceae', 'genero' => 'Ruta']);
    $p = crearPlanta(['nombre' => 'La Ruda', 'cientifico' => 'Ruta graveolens', 'categoria_id' => $this->medicina->id, 'subtema_id' => $this->digestion->id]);

    $this->get("/plantas/{$p->id}")
        ->assertOk()
        ->assertSee('Rutaceae')
        ->assertSee('Clasificación tomada de la Enciclopedia Botánica')
        ->assertDontSee('Identificación taxonómica no confirmada');
});

