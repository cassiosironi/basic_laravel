@include('admin.partials.head')

<div class="login-page">
@include('admin.partials.notify')
    <!-- Theme Toggle -->
    <button class="theme-toggle-float" id="theme-toggle" title="Toggle Light/Dark Mode">
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/>
            <path d="M4.93 4.93l1.41 1.41"/><path d="M17.66 17.66l1.41 1.41"/>
            <path d="M2 12h2"/><path d="M20 12h2"/>
            <path d="M6.34 17.66l-1.41 1.41"/><path d="M19.07 4.93l-1.41 1.41"/>
        </svg>
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">G</div>
                <h1 class="login-title">Bem-vindo</h1>
                <p class="login-subtitle">Insira seu login e senha</p>
            </div>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Login</label>
                    <input type="text" name="login" class="form-control" placeholder="Digite seu login" value="<?php echo old('login'); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                    
                </div>

                <div class="form-row">
                    <!-- <label class="checkbox-label">
                        <input type="checkbox" checked>
                        Lembrar-me
                    </label> -->
                    <a href="#" class="forgot-link">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn btn-primary">
                      Entrar
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </form>


            <p class="login-footer">
                Não possui uma conta? <a href="#">Clique aqui</a>
            </p>
        </div>
    </div>
</div>


@include('admin.partials.footer')
@include('admin.partials.scripts')