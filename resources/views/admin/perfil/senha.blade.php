@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')
  <h1 class="h4 mb-3">Meu perfil</h1>
      <div class="glass-card">
        <form method="POST" action="{{ route('admin.perfil.senha.update') }}" class="needs-validation" novalidate>  
        @csrf
        <div class="mb-3">
          <label class="form-label">Senha atual</label>
          <input type="password" name="senha_atual"
                class="form-control" required>
          <div class="invalid-feedback">
            Informe sua senha atual.
          </div>

        </div>

        <div class="mb-3">
          <label class="form-label">Nova senha</label>
          <input type="password" name="nova_senha"
                class="form-control" required minlength="3">
          <div class="invalid-feedback">
            Informe a nova senha.
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirmar nova senha</label>
          <input type="password" name="nova_senha_confirmation"
                class="form-control" required minlength="3">
          <div class="invalid-feedback">
            Confirme a nova senha.
          </div>
        </div>

        <button type="submit" class="btn btn-primary">
          Alterar senha
        </button>
        <a href="{{ route('admin.index') }}" class="btn btn-outline-light"><i class="bi bi-arrow-return-left"></i> Voltar</a>
        
      </form>
    </div>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')