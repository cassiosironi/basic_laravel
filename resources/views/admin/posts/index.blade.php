@include('admin.partials.head')
@include('admin.partials.menu')
<div class="main-content my-4">

@include('admin.partials.notify')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Posts do Blog</h1>
      <div>  
        <a class="btn btn-success btn-sm" href="{{ route('admin.posts.create') }}">+ Novo</a>
      </div>
  </div>

    <div class="glass-card">
        <div class="card-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-hover align-middle">
            <thead>
                <tr>
                <th class="text-light">ID</th>
                <th class="text-light">Título</th>
                <th class="text-light">Slug</th>
                <th class="text-light">Autor</th>
                <th class="text-light">Criado em</th>
                <th class="text-light text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $p)
                <tr>
                    <td class="text-light"><?php echo $p->id; ?></td>
                    <td class="text-light"><?php echo $p->title; ?></td>
                    <td class="text-light"><?php echo $p->slug; ?></td>
                    <td class="text-light"><?php echo $p->autor_nome; ?></td>
                    <td class="text-light"><?php echo $p->created_at; ?></td>
                    <td class="text-light text-end">
                    <a class="btn btn-outline-secondary btn-sm text-light"
                        href="{{ route('admin.posts.edit', ['id' => $p->id]) }}">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </a>

                    <form class="d-inline"
                            method="POST"
                            action="{{ route('admin.posts.destroy', ['id' => $p->id]) }}">
                        @csrf
                        <button type="submit"
                                class="btn btn-outline-secondary btn-sm text-light"
                                onclick="return confirm('Excluir este post?');">
                        <i class="bi bi-trash me-1"></i>Excluir
                        </button>
                    </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')