@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">

  <div class="row mb-4">
    <div class="col">
      <h1 class="h4 mb-1">Painel Administrativo</h1>
      <p class="text-muted">
        <?php echo 'Gerenciamento do site institucional'; ?>
      </p>
    </div>
  </div>

  <div class="row g-4">

    {{-- Card Banners --}}
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-images me-2"></i> Banners da Home</h5>
          <p class="card-text text-muted">
            Gerencie os banners exibidos na página inicial.
          </p>
        </div>
        <div class="card-footer bg-white border-top-0">
          <a href="{{ route('admin.banners.index') }}">
            Acessar
          </a>
        </div>
      </div>
    </div>

    {{-- Card Sobre --}}
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-file-text me-2"></i> Sobre da Home</h5>
          <p class="card-text text-muted">
            Conteúdo institucional exibido após os banners.
          </p>
        </div>
        <div class="card-footer bg-white border-top-0">
          <a href="{{ route('admin.sobre.edit') }}">
            Editar
          </a>
        </div>
      </div>
    </div>

    @if ($u['nivel'] === 'admin')
    {{-- Card Usuários --}}
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card shadow-sm h-100 border-secondary">
        <div class="card-body">
          <h5 class="card-title"><i class="bi bi-people me-2"></i>Usuários</h5>
          <p class="card-text text-muted">
            Gerenciamento de usuários do painel.
          </p>
        </div>
        <div class="card-footer bg-white border-top-0">
          <a href="{{ route('admin.usuarios.index') }}">
            Acessar
          </a>
        </div>
      </div>
    </div>
    @endif

  </div>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')
