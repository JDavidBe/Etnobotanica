@extends('layouts.admin')

@section('title', 'Subtemas')
@section('admin-title', 'Subtemas')

@section('content')

@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

<div class="tbl-card">
  <div class="tbl-hdr">
    <h3>Subtemas</h3>
    <button class="ab-add" onclick="abrirModalSub()">
      <i class="fas fa-plus"></i> Nuevo
    </button>
  </div>

  {{-- Filtro rápido por categoría --}}
  <div style="padding:14px 20px 0;display:flex;gap:8px;flex-wrap:wrap">
    <a href="{{ route('admin.subtemas.index') }}"
       class="f-btn {{ !request('cat') ? 'on' : '' }}">Todos</a>
    @foreach($categorias as $cat)
      <a href="{{ route('admin.subtemas.index', ['cat' => $cat->id]) }}"
         class="f-btn {{ request('cat') == $cat->id ? 'on' : '' }}">{{ $cat->nombre }}</a>
    @endforeach
  </div>

  <table style="margin-top:4px">
    <thead>
      <tr>
        <th>Categoría</th>
        <th>Subtema</th>
        <th>Plantas</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($subtemas as $sub)
        <tr>
          <td>
            <span style="background:var(--pale);color:var(--verde);padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:700">
              {{ $sub->categoria->nombre }}
            </span>
          </td>
          <td><strong>{{ $sub->nombre }}</strong></td>
          <td>{{ $sub->plantas_count }}</td>
          <td>
            <button class="abtn ab-ok"
                    onclick="editarSub({{ $sub->id }}, {{ $sub->categoria_id }}, '{{ addslashes($sub->nombre) }}')">
              Editar
            </button>
            <form method="POST" action="{{ route('admin.subtemas.destroy', $sub) }}"
                  style="display:inline"
                  id="form-del-sub-{{ $sub->id }}">
              @csrf @method('DELETE')
              <button type="button" class="abtn ab-del"
                      onclick="alpineConfirm(
                        '¿Eliminar subtema?',
                        '¿Eliminar &laquo;{{ addslashes($sub->nombre) }}&raquo;? Las plantas asociadas quedarán sin subtema.',
                        'form-del-sub-{{ $sub->id }}'
                      )">Eliminar</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" style="text-align:center;color:var(--texto-suave);padding:30px">
            No hay subtemas registrados.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Modal crear / editar subtema --}}
<div id="modal-subtema" class="modal-ov">
  <div class="modal" style="max-width:460px">
    <div class="modal-hdr">
      <h3><i class="fas fa-tag"></i> <span id="modal-sub-titulo">Nuevo Subtema</span></h3>
      <button class="modal-close" onclick="cerrarModal('modal-subtema')">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form method="POST" id="form-subtema" action="{{ route('admin.subtemas.store') }}">
      @csrf
      <input type="hidden" name="_method" id="sub-method" value="POST">
      <div class="modal-body">
        <div class="f-group">
          <label>Categoría *</label>
          <select name="categoria_id" id="sub-categoria" required>
            <option value="">Seleccionar…</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="f-group">
          <label>Nombre del subtema *</label>
          <input type="text" name="nombre" id="sub-nombre"
                 placeholder="Ej: Plantas medicinales digestivas" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="cerrarModal('modal-subtema')">Cancelar</button>
        <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Guardar</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function abrirModalSub() {
  document.getElementById('modal-sub-titulo').textContent = 'Nuevo Subtema';
  document.getElementById('sub-method').value     = 'POST';
  document.getElementById('sub-nombre').value     = '';
  document.getElementById('sub-categoria').value  = '';
  document.getElementById('form-subtema').action  = '{{ route('admin.subtemas.store') }}';
  abrirModal('modal-subtema');
}

function editarSub(id, categoriaId, nombre) {
  document.getElementById('modal-sub-titulo').textContent = 'Editar Subtema';
  document.getElementById('sub-method').value     = 'PUT';
  document.getElementById('sub-nombre').value     = nombre;
  document.getElementById('sub-categoria').value  = categoriaId;
  document.getElementById('form-subtema').action  = `/admin/subtemas/${id}`;
  abrirModal('modal-subtema');
}
</script>
@endpush
