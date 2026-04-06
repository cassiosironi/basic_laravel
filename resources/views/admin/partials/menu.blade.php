<?php $u = session('admin_user'); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">

    {{ route('admin.index') }}Admin CMS</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          {{ route('admin.banners.index') }}Banners</a>
        </li>
        <li class="nav-item">
          {{ route('admin.sobre.edit') }}Sobre (Home)</a>
        </li>
      </ul>

      @if ($u)
        <div class="d-flex align-items-center text-white gap-3">
          <span class="small">
            <?php echo $u['nome']; ?>
          </span>

          {{ route('admin.logout') }}
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">
              Sair
            </button>
          </form>
        </div>
      @endif

    </div>
  </div>
</nav>