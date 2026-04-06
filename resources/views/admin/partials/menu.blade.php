<?php $u = session('admin_user'); ?>

<nav class="navbar navbar-dark bg-primary">
  <div class="container">

    {{ route('admin.index') }}Admin CMS</a>

    <ul class="navbar-nav flex-row gap-3">

      @if (in_array($u['nivel'], ['admin','editor']))
        <li class="nav-item">
          {{ route('admin.banners.index') }}Banners</a>
        </li>
        <li class="nav-item">
          {{ route('admin.sobre.edit') }}Sobre</a>
        </li>
      @endif

      @if ($u['nivel'] === 'admin')
        <li class="nav-item">
          Usuários
        </li>
      @endif

      <li class="nav-item text-white ms-3">
        <?php echo $u['nome']; ?>
      </li>

      <li class="nav-item ms-2">
        {{ route('admin.logout') }}
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-light">
            Sair
          </button>
        </form>
      </li>

    </ul>

  </div>
</nav>