@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Exibir Banner #<?php echo $banner->id; ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="/admin/banners">Voltar</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="row g-3">
        <div class="col-md-5">
          <img src="{{ asset($banner->image) }}" class="img-fluid rounded" alt="banner">
        </div>

        <div class="col-md-7">
          <div class="mb-2">
            <strong>Imagem:</strong>
            <div class="text-muted"><?php echo $banner->image; ?></div>
          </div>

          <div class="mb-2">
            <strong>Título:</strong>
            <div><?php echo $banner->title; ?></div>
          </div>

          <div class="mb-2">
            <strong>Subtexto:</strong>
            <div class="text-muted"><?php echo $banner->subtitle; ?></div>
          </div>

          <div class="mt-3 d-flex gap-2">
            <a class="btn btn-primary btn-sm" href="/admin/banners/<?php echo $banner->id; ?>/edit">Editar</a>

            <form action="/admin/banners/<?php echo $banner->id; ?>/destroy" method="post" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('Excluir este banner?');">
                Excluir
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')