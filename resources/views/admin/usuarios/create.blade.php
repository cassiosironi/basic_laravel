@include('admin.partials.head')
@include('admin.partials.menu')

<div class="container my-4">
  <h1 class="h4">Novo Usuário</h1>


   <form method="POST" action="{{ route('admin.usuarios.store') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-3">
      <label>Nome</label>
      <input class="form-control" name="nome" required>
      <div class="invalid-feedback">
          Campo obrigatório!
      </div>
    </div>

    <div class="mb-3">
      <label>Login</label>
      <input class="form-control" name="login" required>
      <div class="invalid-feedback">
          Campo obrigatório!
      </div>
    </div>

    <div class="mb-3">
      <label>Senha</label>
      <input type="password" class="form-control" name="senha" required>
      <div class="invalid-feedback">
          Campo obrigatório!
      </div>
    </div>

    <div class="mb-3">
      <label>Nível</label>
      <select class="form-select" name="nivel">
        <option value="admin">Admin</option>
        <option value="editor">Editor</option>
        <option value="client">Client</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select class="form-select" name="ativo">
        <option value="1">Ativo</option>
        <option value="0">Inativo</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>    
     <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
        Voltar
    </a>
  
  </form>

   

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')