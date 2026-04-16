@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')

  <div class="glass-card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 mb-0">
            Editar Usuário
            </h1>

            <a href="{{ route('admin.usuarios.logs', ['id' => $usuario->id]) }}" class="btn btn-outline-light">
            <i class="bi bi-activity"></i> Ver logs de login
            </a>
        </div>

            <form method="POST" action="{{ route('admin.usuarios.update', ['id' => $usuario->id]) }}" class="needs-validation" novalidate>  
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input class="form-control" name="nome"
                            value="<?php echo $usuario->nome; ?>" required>                    
                    <div class="invalid-feedback">
                        Campo obrigatório!
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Login</label>
                    <input class="form-control" value="<?php echo $usuario->login; ?>" disabled>
                    <small class="text-warning">Login não pode ser alterado.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nova senha</label>
                    <input type="password" class="form-control" name="senha">
                    <small class="text-warning">Preencha somente se quiser alterar.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nível</label>
                    <select class="form-select text-light bg-dark" name="nivel">
                        <option value="admin"  <?php echo $usuario->nivel === 'admin'  ? 'selected' : ''; ?>>Admin</option>
                        <option value="editor" <?php echo $usuario->nivel === 'editor' ? 'selected' : ''; ?>>Editor</option>
                        <option value="client" <?php echo $usuario->nivel === 'client' ? 'selected' : ''; ?>>Client</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select text-light bg-dark" name="ativo">
                        <option value="1" <?php echo $usuario->ativo == 1 ? 'selected' : ''; ?>>Ativo</option>
                        <option value="0" <?php echo $usuario->ativo == 0 ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-light mt-5"> <i class="bi bi-arrow-return-left"></i> Voltar</a>

            </form>
        </div>
</div>


</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')