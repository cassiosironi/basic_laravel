<?php $u = session('admin_user'); ?>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">CS</div>
        <span class="logo-text">CassioDash</span>
    </div>

    <ul class="nav-menu">
        <li class="nav-section">
            <span class="nav-section-title">Main Menu</span>
            <ul>
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link">
                        <i class="bi bi-speedometer2"></i>                          
                        Dashboard
                    </a>
                </li>
                @if (in_array($u['nivel'], ['admin', 'editor']))
                <li class="nav-item">
                    <a href="{{ route('admin.banners.index') }}" class="nav-link">
                        <i class="bi bi-images"></i>                            
                        Banners
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.sobre.edit') }}" class="nav-link">
                        <i class="bi bi-box-seam"></i>
                        Sobre
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.chamados.index') }}">
                        <i class="bi bi-ticket-detailed me-2"></i>Chamados
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-gear"></i>
                        Configurações
                        <span class="nav-badge">New</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <li class="nav-section">
            <span class="nav-section-title">Perfil</span>
            <ul>
                @if ($u['nivel'] === 'admin')
                <li class="nav-item">
                    <a href="{{ route('admin.usuarios.index') }}" class="nav-link">
                        <i class="bi bi-people"></i>
                        Usuários
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="nav-link" type="submit">
                                <i class="bi bi-escape"></i>
                                Sair
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-profile">
            <a href="{{ route('admin.perfil.senha') }}" class="nav-link">
                <div class="user-avatar"><?php echo substr($u['nome'], 0, 2); ?></div>
                <div class="user-info">
                    <div class="user-name"><?php echo $u['nome']; ?></div>
                    <div class="user-role"><?php echo $u['nivel']; ?></div>
                </div>
                <i class="bi bi-person"></i>
            </a>
        </div>
    </div>
</aside>
<!-- End Sidebar -->