@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">
  <h1 class="h4 mb-3">Editar Post #<?php echo $post->id; ?></h1>

  <form method="POST"
        action="{{ route('admin.posts.update', ['id' => $post->id]) }}"
        enctype="multipart/form-data">
    @csrf

    @include('admin.posts.form', ['post' => $post])

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>Salvar alterações
      </button>
      <a class="btn btn-outline-secondary" href="{{ route('admin.posts.index') }}">Voltar</a>
      <a class="btn btn-outline-dark" target="_blank"
         href="{{ route('site.blog.show', ['slug' => $post->slug]) }}">
         <i class="bi bi-box-arrow-up-right me-1"></i>Ver no site
      </a>
    </div>
  </form>
</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')
