@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">
      Editar Usuário
    </h1>

    <a href="{{ route('admin.usuarios.logs', ['id' => $usuario->id]) }}" class="btn btn-outline-secondary">
      Ver logs de login
    </a>
  </div>

    <form method="POST" action="{{ route('admin.usuarios.update', ['id' => $usuario->id]) }}">  
        @csrf

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input class="form-control" name="nome"
                    value="<?php echo $usuario->nome; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Login</label>
            <input class="form-control" value="<?php echo $usuario->login; ?>" disabled>
            <small class="text-muted">Login não pode ser alterado.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Nova senha</label>
            <input type="password" class="form-control" name="senha">
            <small class="text-muted">Preencha somente se quiser alterar.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Nível</label>
            <select class="form-select" name="nivel">
                <option value="admin"  <?php echo $usuario->nivel === 'admin'  ? 'selected' : ''; ?>>Admin</option>
                <option value="editor" <?php echo $usuario->nivel === 'editor' ? 'selected' : ''; ?>>Editor</option>
                <option value="client" <?php echo $usuario->nivel === 'client' ? 'selected' : ''; ?>>Client</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="ativo">
                <option value="1" <?php echo $usuario->ativo == 1 ? 'selected' : ''; ?>>Ativo</option>
                <option value="0" <?php echo $usuario->ativo == 0 ? 'selected' : ''; ?>>Inativo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar alterações</button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">Voltar</a>

    </form>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')