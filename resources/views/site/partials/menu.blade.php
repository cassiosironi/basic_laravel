<?php $u = session('admin_user'); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('site.home') }}">Home</a>
    
      <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto">

            {{-- outros itens --}}

            <li class="nav-item">
               <a class="nav-link" href="{{ route('site.chamados.create') }}">
                <i class="bi bi-life-preserver me-1"></i>
                Abrir chamado
              </a>
            </li>

          </ul>
      </div>

       @if (in_array($u['nivel'], ['admin', 'editor', 'client']))
        <div class="ms-auto text-light d-flex mt-3">
          <p>Olá <?php echo $u['nome']; ?></p>
          <form action="{{ route('admin.logout') }}" method="POST">
              @csrf
              <button class="mx-4 nav-link" type="submit">
                      <i class="bi bi-escape"></i>
                      Sair
              </button>
          </form>          
        </div>
      @endif

    <div class="ms-auto">
      <a class="btn btn-outline-light btn-sm" href="{{ route('admin.index') }}">Admin</a>
    </div>
  </div>
</nav>