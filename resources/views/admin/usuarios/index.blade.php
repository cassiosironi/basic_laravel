@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')

  <div class="d-flex justify-content-between mb-3">
    <h1 class="h4">Usuários</h1>
      <a class="btn btn-success btn-sm" href="{{ route('admin.usuarios.create') }}">
        + Novo Usuário
      </a>
  </div>

  
  <div class="glass-card">
    <div class="card-body">
      <table id="datatable" class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th class="text-light">Nome</th>
            <th class="text-light">Login</th>
            <th class="text-light">Nível</th>
            <th class="text-light">Status</th>
            <th class="text-light" width="180">Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($usuarios as $u)
            <tr>
              <td class="text-light"><?php echo $u->nome; ?></td>
              <td class="text-light"><?php echo $u->login; ?></td>
              <td class="text-light"><?php echo $u->nivel; ?></td>
              <td class="text-light"><?php echo $u->ativo ? 'Ativo' : 'Inativo'; ?></td>
              <td class="text-light">
                <a class="btn btn-outline-secondary btn-sm text-light" href="{{ route('admin.usuarios.edit', ['id'=>$u->id]) }}"><i class="bi bi-pencil"></i> Editar</a>

                <form method="POST" action="{{ route('admin.usuarios.delete', ['id'=>$u->id]) }}">
                  @csrf
                  <button class="btn btn-sm btn-danger"
                    onclick="return confirm('Excluir este usuário?')">
                    <i class="bi bi-trash"></i> Excluir
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

@include('admin.partials.footer')
@include('admin.partials.scripts')