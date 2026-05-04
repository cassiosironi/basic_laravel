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

  {{-- CONSULTA CEP (pública, sem login) --}}
  <div class="card shadow-sm mt-5">
      <div class="card-body">
        <h2 class="h5 mb-3">Consultar endereço por CEP</h2>

        <form action="{{ route('site.home') }}" method="get">
       
          <div class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
              <label class="form-label">CEP</label>
              <input type="text"
                    name="cep"
                    class="form-control"
                    placeholder="Ex: 01001000"
                    value="<?php echo isset($cepInput) ? $cepInput : ''; ?>"
                    maxlength="10">
              <div class="form-text">Digite 8 dígitos (pode usar hífen).</div>
            </div>

            <div class="col-12 col-md-3">
              <button type="submit" class="btn btn-primary w-100">
                Consultar
              </button>
            </div>

            <div class="col-12 col-md-3">
              <a href="{{ route('site.home') }}" class="btn btn-outline-light text-dark">Limpar</a>
            </div>
          </div>
        </form>

        {{-- ERRO --}}
        <?php if (!empty($cepError)) { ?>
          <div class="alert alert-danger mt-3 mb-0">
            <?php echo $cepError; ?>
          </div>
        <?php } ?>

        {{-- RESULTADO --}}
        <?php if (!empty($cepResult)) { ?>
          <div class="mt-3">
            <div class="alert alert-success">
              <strong>Endereço encontrado:</strong>
            </div>

            <div class="row g-2">
              <div class="col-md-6">
                <div class="small text-muted">Logradouro</div>
                <div><?php echo $cepResult['logradouro'] ?? ''; ?></div>
              </div>

              <div class="col-md-6">
                <div class="small text-muted">Bairro</div>
                <div><?php echo $cepResult['bairro'] ?? ''; ?></div>
              </div>

              <div class="col-md-6">
                <div class="small text-muted">Cidade</div>
                <div><?php echo $cepResult['localidade'] ?? ''; ?></div>
              </div>

              <div class="col-md-2">
                <div class="small text-muted">UF</div>
                <div><?php echo $cepResult['uf'] ?? ''; ?></div>
              </div>

              <div class="col-md-4">
                <div class="small text-muted">CEP</div>
                <div><?php echo $cepResult['cep'] ?? ''; ?></div>
              </div>
            </div>
          </div>
        <?php } ?>

      </div>

  </div>

</div>

@include('site.partials.footer')
@include('site.partials.scripts')