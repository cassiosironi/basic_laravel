<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="{{ route('admin/home') }}">Admin CMS</a>

    <div class="ms-auto d-flex gap-2">
      <a class="btn btn-outline-light btn-sm" href="{{ route('site/home') }}">Ver Site</a>
      <a class="btn btn-light btn-sm" href="{{ route('admin/banners/index') }}">Banners</a>
      <a class="btn btn-light btn-sm" href="{{ route('admin/sobre/edit') }}">Sobre</a>
    </div>
  </div>
</nav>