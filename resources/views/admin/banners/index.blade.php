@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Banners - Listagem</h1>
      <div>
      <a href="{{ route('admin.banners.reorder') }}" class="btn btn-outline-light btn-sm">  
          <i class="bi bi-arrow-repeat"></i> Reordenar Banners
        </a>
        <a class="btn btn-success btn-sm" href="/admin/banners/create">+ Novo</a>
      </div>
  </div>

  <div class="glass-card">
    <div class="card-body">

      <div class="table-responsive">
        <table id="datatable" class="table table-hover align-middle">
          <thead>
            <tr>
              <th class="text-light">ID</th>
              <th class="text-light">Imagem</th>
              <th class="text-light">Título</th>
              <th class="text-light">Subtexto</th>
              <th class="text-end text-light">Ações</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($banners as $banner)
              <tr>
                <td class="text-light"><?php echo $banner->id; ?></td>
                <td style="width: 140px;">
                  <img src="{{ asset($banner->image) }}" class="img-fluid rounded" alt="banner" loading="lazy">
                </td>
                <td class="text-light"><?php echo $banner->title; ?></td>
                <td class="text-light"><?php echo $banner->subtitle; ?></td>

                <td class="text-end">

                  <a class="btn btn-outline-secondary btn-sm text-light" href="/admin/banners/<?php echo $banner->id; ?>/edit">
                    <i class="bi bi-pencil"></i> Editar
                  </a>

                  <form action="/admin/banners/<?php echo $banner->id; ?>/destroy" method="post" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"
                      onclick="return confirm('Excluir este banner?');">
                      <i class="bi bi-trash"></i> Excluir
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>

      @if (count($banners) == 0)
        <div class="alert alert-warning mb-0">
          <?php echo 'Nenhum banner encontrado.'; ?>
        </div>
      @endif

    </div>
  </div>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')