@include('site.partials.head')
@include('site.partials.menu')

<div class="container my-4">
  <h1 class="h4 mb-3">Blog</h1>

  <div class="row g-3">
    @forelse ($posts as $p)
      <?php
        $cover = !empty($p->cover_image) ? $p->cover_image : 'img/posts/default.png';
      ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <img src="<?php echo asset($cover); ?>"
               class="card-img-top"
               alt="Capa"
               loading="lazy"
               style="height:180px;object-fit:cover;">

          <div class="card-body">
            <h5 class="card-title"><?php echo $p->title; ?></h5>
            <p class="card-text text-muted"><?php echo $p->summary; ?></p>
          </div>

          <div class="card-footer bg-white">
            <a class="btn btn-outline-primary btn-sm"
               href="{{ route('site.blog.show', ['slug' => $p->slug]) }}">
              Ler mais
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-warning">
          <?php echo 'Nenhuma postagem publicada ainda.'; ?>
        </div>
      </div>
    @endforelse
  </div>
</div>

@include('site.partials.footer')
@include('site.partials.scripts')