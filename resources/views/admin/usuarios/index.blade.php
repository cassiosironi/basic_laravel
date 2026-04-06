@include('admin.partials.head')
@include('admin.partials.menu')

<div class="container my-4">

  <div class="d-flex justify-content-between mb-3">
    <h1 class="h4">Usuários</h1>
      <a href="{{ route('admin.usuarios.create') }}">
        Novo Usuário
      </a>
  </div>

  <table id="datatable" class="table table-bordered table-hover align-middle">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Login</th>
        <th>Nível</th>
        <th>Status</th>
        <th width="180">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($usuarios as $u)
        <tr>
          <td><?php echo $u->nome; ?></td>
          <td><?php echo $u->login; ?></td>
          <td><?php echo $u->nivel; ?></td>
          <td><?php echo $u->ativo ? 'Ativo' : 'Inativo'; ?></td>
          <td>
            <a href="{{ route('admin.usuarios.edit', ['id'=>$u->id]) }}">Editar</a>

            <form method="POST" action="{{ route('admin.usuarios.delete', ['id'=>$u->id]) }}">
              @csrf
              <button class="btn btn-sm btn-danger"
                onclick="return confirm('Excluir este usuário?')">
                Excluir
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')