@include('site.partials.head')
@include('site.partials.menu')

<div class="container my-4">

  <h1 class="h3">Home</h1>

  {{-- BANNERS --}}
  <div class="row g-3">
    @forelse ($banners as $banner)
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
          <img src="{{ asset($banner->image) }}" class="img-fluid" alt="banner">
          <div class="card-body">
            <h5><?php echo $banner->title; ?></h5>
            <p class="text-muted"><?php echo $banner->subtitle; ?></p>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <?php echo 'Nenhum banner cadastrado.'; ?>
      </div>
    @endforelse
  </div>

  {{-- SOBRE --}}
  <div class="row mt-5 align-items-center">
    @if ($sobre)
      <div class="col-md-5">
        <img src="{{ asset($sobre->image) }}" class="img-fluid rounded" alt="sobre">
      </div>
      <div class="col-md-7">
        <h2 class="h4"><?php echo $sobre->title; ?></h2>
        <p class="text-muted"><?php echo $sobre->text; ?></p>
      </div>
    @else
      <div class="col-12">
        <?php echo 'Seção Sobre ainda não configurada no painel.'; ?>
      </div>
    @endif
  </div>

</div>

@include('site.partials.footer')
@include('site.partials.scripts')