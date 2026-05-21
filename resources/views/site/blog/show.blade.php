@include('site.partials.head')
@include('site.partials.menu')

<?php
$cover = !empty($post->cover_image) ? $post->cover_image : 'img/posts/default.png';
?>

<div class="container my-4">
  <a href="{{ route('site.blog.index') }}" class="btn btn-link p-0 mb-3">← Voltar</a>

  <h1 class="h3"><?php echo $post->title; ?></h1>
  <div class="text-muted mb-3"><?php echo $post->created_at; ?></div>

  <img src="<?php echo asset($cover); ?>"
       class="img-fluid rounded mb-4"
       alt="Capa"
       loading="lazy">

  <div class="mb-3 text-muted">
    <?php echo $post->summary; ?>
  </div>

  <div class="card">
    <div class="card-body">
      <?php
        // Conteúdo vem como HTML do WYSIWYG
        echo $post->content;
      ?>
    </div>
  </div>
</div>

@include('site.partials.footer')
@include('site.partials.scripts')