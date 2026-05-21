@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')

  <h1 class="h4 mb-3">Novo Post</h1>

   <div class="glass-card">
      <div class="card-body">
        <form method="POST"
              action="{{ route('admin.posts.store') }}"
              enctype="multipart/form-data">
          @csrf

          @include('admin.posts.form')

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save me-1"></i>Salvar
            </button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.posts.index') }}">Voltar</a>
          </div>
        </form>
      </div>
    </div>
    
  </div>

@include('admin.partials.footer')
@include('admin.partials.scripts')