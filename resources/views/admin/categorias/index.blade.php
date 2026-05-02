@extends('layouts.admin')

@section('title', 'Categorías')
@section('admin-title', 'Categorías')

@section('content')

@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

<div class="tbl-card">
  <div class="tbl-hdr">
    <h3>Categorías</h3>
    <button class="ab-add" onclick="nuevaCategoria()">
      <i class="fas fa-plus"></i> Nueva
    </button>
  </div>

  <table>
    <thead>
      <tr>
        <th>Icono</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Orden</th>
        <th>Subtemas</th>
        <th>Plantas</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categorias as $cat)
        <tr>
          <td><i class="{{ $cat->icono }}" style="font-size:1.2rem;color:var(--verde-mid)"></i></td>
          <td><strong>{{ $cat->nombre }}</strong></td>
          <td>{{ $cat->descripcion }}</td>
          <td>{{ $cat->orden }}</td>
          <td>{{ $cat->subtemas_count }}</td>
          <td>{{ $cat->plantas_count }}</td>
          <td>
            <a href="{{ route('admin.subtemas.index', ['cat' => $cat->id]) }}"
               class="abtn" style="background:var(--pale);color:var(--verde);border:1px solid #c8e6c9">
              <i class="fas fa-tags"></i> Subtemas
            </a>
            <button class="abtn ab-ok"
                    onclick="editarCategoria({{ $cat->id }}, '{{ addslashes($cat->nombre) }}', '{{ addslashes($cat->descripcion) }}', '{{ $cat->icono }}', {{ $cat->orden }})">
              Editar
            </button>
            <form method="POST" action="{{ route('admin.categorias.destroy', $cat) }}"
                  style="display:inline"
                  id="form-del-cat-{{ $cat->id }}">
              @csrf @method('DELETE')
              <button type="button" class="abtn ab-del"
                      onclick="alpineConfirm(
                        '¿Eliminar categoría?',
                        '¿Eliminar &laquo;{{ addslashes($cat->nombre) }}&raquo;? Se eliminarán sus subtemas y plantas.',
                        'form-del-cat-{{ $cat->id }}'
                      )">Eliminar</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="text-align:center;color:var(--texto-suave);padding:30px">
            No hay categorías registradas.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Modal nueva categoría --}}
<div id="modal-categoria" class="modal-ov">
  <div class="modal" style="max-width:500px">
    <div class="modal-hdr">
      <h3><i class="fas fa-folder"></i> <span id="modal-cat-titulo">Nueva Categoría</span></h3>
      <button class="modal-close" onclick="cerrarModal('modal-categoria')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="form-categoria" action="{{ route('admin.categorias.store') }}">
      @csrf
      <input type="hidden" name="_method" id="cat-method" value="POST">
      <input type="hidden" name="categoria_id" id="cat-id" value="">
      <div class="modal-body">
        <div class="f-group">
          <label>Nombre *</label>
          <input type="text" name="nombre" id="cat-nombre" placeholder="Ej: Medicina" required>
        </div>
        <div class="f-group">
          <label>Descripción</label>
          <textarea name="descripcion" id="cat-descripcion" rows="2" placeholder="Breve descripción…"></textarea>
        </div>
        <div class="f-group">
          <label>Icono (FontAwesome)</label>
          <input type="text" name="icono" id="cat-icono" placeholder="Ej: fas fa-leaf" value="fas fa-leaf">
        </div>
        <div class="f-group">
          <label>Orden</label>
          <input type="number" name="orden" id="cat-orden" placeholder="0" min="0" value="0">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="cerrarModal('modal-categoria')">Cancelar</button>
        <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function nuevaCategoria() {
  document.getElementById('modal-cat-titulo').textContent = 'Nueva Categoría';
  document.getElementById('cat-id').value          = '';
  document.getElementById('cat-method').value      = 'POST';
  document.getElementById('cat-nombre').value      = '';
  document.getElementById('cat-descripcion').value = '';
  document.getElementById('cat-icono').value       = 'fas fa-leaf';
  document.getElementById('cat-orden').value       = '0';
  document.getElementById('form-categoria').action = '{{ route('admin.categorias.store') }}';
  abrirModal('modal-categoria');
}

function editarCategoria(id, nombre, descripcion, icono, orden) {
  document.getElementById('modal-cat-titulo').textContent = 'Editar Categoría';
  document.getElementById('cat-id').value          = id;
  document.getElementById('cat-method').value      = 'PUT';
  document.getElementById('cat-nombre').value      = nombre;
  document.getElementById('cat-descripcion').value = descripcion;
  document.getElementById('cat-icono').value       = icono;
  document.getElementById('cat-orden').value       = orden;
  document.getElementById('form-categoria').action = `/admin/categorias/${id}`;
  abrirModal('modal-categoria');
}
</script>
@endpush
