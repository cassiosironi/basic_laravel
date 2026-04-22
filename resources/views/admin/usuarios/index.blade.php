@include('admin.partials.head')
@include('admin.partials.menu')

<main class="main-content">
@include('admin.partials.notify')
    <!-- Users Table -->
    <section class="content-grid" style="grid-template-columns: 1fr;">
        <div class="glass-card table-card" style="grid-column: span 1;">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Lista de Usuários</h2>
                    <p class="card-subtitle">Manage your user base</p>
                </div>
                <div class="card-actions">
                     <a class="card-btn" href="{{ route('admin.usuarios.create') }}">
                      + Novo Usuário
                    </a>                    
                </div>
            </div>
            <div class="table-wrapper">
                <table id="datatable" class="data-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Nível</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($usuarios as $u)
                          <tr>
                              <td><div class="table-user"><div class="table-avatar" style="background: linear-gradient(135deg, var(--emerald-light), var(--emerald));"><?php echo substr($u->nome, 0, 2); ?></div><div class="table-user-info"><span class="table-user-name"><?php echo $u->nome; ?></span><span class="table-user-email">email</span></div></div></td>
                              <td><?php echo $u->nivel; ?></td>
                              <td><span class="status-badge completed"><?php echo $u->ativo ? 'Ativo' : 'Inativo'; ?></span></td>
                              <td class="d-flex justify-content-around">
                                <form method="POST" action="{{ route('admin.usuarios.delete', ['id'=>$u->id]) }}">
                                  @csrf
                                  <button class="card-btn btn-danger"
                                    onclick="return confirm('Excluir este usuário?')">
                                    <i class="bi bi-trash"></i> Excluir
                                  </button>
                                </form>
                                 <a class="card-btn btn-success" href="{{ route('admin.usuarios.edit', ['id'=>$u->id]) }}"><i class="bi bi-pencil"></i> Editar</a>
                              </td>
                          </tr>
                        @endforeach
                     
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

@include('admin.partials.footer')
@include('admin.partials.scripts')