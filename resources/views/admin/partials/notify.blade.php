@if (session('notify'))
  <?php $n = session('notify'); ?>
  <div class="alert alert-<?php echo $n['type']; ?> alert-dismissible fade show" role="alert">
    <?php echo $n['message']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
  </div>
@endif