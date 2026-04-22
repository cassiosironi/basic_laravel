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
                    <p class="card-subtitle">Administração da base de usuários do sistema.</p>
                </div>
                <div class="card-actions">
                     <a class="card-btn" href="{{ route('admin.usuarios.create') }}">
                      + Novo Usuário
                    </a>                    
                </div>
            </div>
            <div>
              <form method="GET" action="{{ route('admin.usuarios.index') }}" class="row g-3 pb-3 mb-5">
                <div class="col-md-4">
                  <label class="form-label">Filtrar por Nível</label>
                  <select name="nivel" class="form-select bg-light">
                    <option value="">Todos</option>
                    <option value="admin"  <?php echo request('nivel') === 'admin'  ? 'selected' : ''; ?>>Admin</option>
                    <option value="editor" <?php echo request('nivel') === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    <option value="client" <?php echo request('nivel') === 'client' ? 'selected' : ''; ?>>Client</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Filtrar por Status</label>
                  <select name="ativo" class="form-select bg-light">
                    <option value="">Todos</option>
                    <option value="1" <?php echo request('ativo') === '1' ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo request('ativo') === '0' ? 'selected' : ''; ?>>Inativo</option>
                  </select>
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                  <button type="submit" class="btn btn-primary">
                    Filtrar
                  </button>

                  <a class="card-btn" href="{{ route('admin.usuarios.index') }}">                  
                    Limpar
                  </a>
                </div>

              </form>

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