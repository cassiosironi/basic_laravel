@if (session('notify'))
  <?php $n = session('notify'); ?>
  <div class="container mt-3">
    <div class="alert alert-<?php echo $n['type']; ?> alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-2"></i> <?php echo $n['message']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  </div>
@endif

@if ($errors->any())
  <div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong><i class="bi bi-exclamation-circle me-2"></i> Erros encontrados:</strong>
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li><?php echo $e; ?></li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  </div>
@endif
``