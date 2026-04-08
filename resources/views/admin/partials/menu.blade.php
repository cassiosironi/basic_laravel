<?php $u = session('admin_user'); ?>

<nav class="navbar navbar-dark bg-primary">
  <div class="container">

    <a href="{{ route('admin.index') }}">Admin CMS</a>

    <ul class="navbar-nav flex-row gap-3">

      <li class="nav-item">
          <a href="{{ route('admin.index') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
      </li>

      @if (in_array($u['nivel'], ['admin','editor']))
        <li class="nav-item">
          <a href="{{ route('admin.banners.index') }}"><i class="bi bi-images me-2"></i> Banners</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.sobre.edit') }}"><i class="bi bi-file-text me-2"></i> Sobre</a>
        </li>
      @endif
      
      @if ($u['nivel'] === 'admin')
        <li class="nav-item">
          <a href="{{ route('admin.usuarios.index') }}"><i class="bi bi-people me-2"></i> Usuários</a>
        </li>
      @endif

      <li class="nav-item text-white ms-3">
        <i class="bi-person-lines-fill me-2"></i> <?php echo $u['nome']; ?>
      </li>
      
      <li class="nav-item">
        <a href="{{ route('admin.perfil.senha') }}"><i class="bi bi-person-badge me-2"></i> Meu Perfil</a>
      </li>

      <li class="nav-item ms-2">
          <a href="{{ route('admin.logout') }}" class="btn btn-sm btn-outline-light">
            <i class="bi bi-box-arrow-right me-2"></i> Sair
          </a>
      </li>

    </ul>

  </div>
</nav>