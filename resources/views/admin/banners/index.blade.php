@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Banners - Listagem</h1>
      <div>
      <a href="{{ route('admin.banners.reorder') }}" class="btn btn-outline-secondary btn-sm">  
          Reordenar Banners
        </a>
        <a class="btn btn-success btn-sm" href="/admin/banners/create">+ Novo</a>
      </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="table-responsive">
        <table id="datatable" class="table table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Imagem</th>
              <th>Título</th>
              <th>Subtexto</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($banners as $banner)
              <tr>
                <td><?php echo $banner->id; ?></td>
                <td style="width: 140px;">
                  <img src="{{ asset($banner->image) }}" class="img-fluid rounded" alt="banner">
                </td>
                <td><?php echo $banner->title; ?></td>
                <td class="text-muted"><?php echo $banner->subtitle; ?></td>

                <td class="text-end">

                  <a class="btn btn-outline-secondary btn-sm" href="/admin/banners/<?php echo $banner->id; ?>/edit">
                    Editar
                  </a>

                  <form action="/admin/banners/<?php echo $banner->id; ?>/destroy" method="post" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"
                      onclick="return confirm('Excluir este banner?');">
                      Excluir
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach

            <?php
            // exemplo extra de while (didático). Normalmente você não misturaria assim:
            // echo 'Você pode usar while com arrays/iteradores se quiser.';
            ?>
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