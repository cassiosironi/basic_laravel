@include('site.partials.head')
@include('site.partials.menu')

<div class="container my-4">
  <h1 class="h4 mb-3">Abertura de Chamado</h1>

  {{-- Notificações --}}
  @if (session('notify'))
    <?php $n = session('notify'); ?>
    <div class="alert alert-<?php echo $n['type']; ?>">
      <?php echo $n['message']; ?>
    </div>
  @endif

   <form action="{{ route('site.chamados.store') }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
 
    @csrf

    <div class="mb-3">
      <label class="form-label">Título</label>
      <input type="text" name="titulo" class="form-control"
             value="<?php echo old('titulo'); ?>" required maxlength="120">
    </div>

    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" class="form-control" rows="5"
                required><?php echo old('descricao'); ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Tipo</label>
      <select name="tipo" class="form-select" required>
        <option value="">Selecione…</option>
        <option value="rede" <?php echo old('tipo') === 'rede' ? 'selected' : ''; ?>>Rede</option>
        <option value="sistema" <?php echo old('tipo') === 'sistema' ? 'selected' : ''; ?>>Sistema</option>
        <option value="hardware" <?php echo old('tipo') === 'hardware' ? 'selected' : ''; ?>>Hardware</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Anexo (PDF ou imagem)</label>
      <input type="file" name="anexo" class="form-control"
             accept=".pdf,.jpg,.jpeg">
      <div class="form-text">
        Formatos permitidos: PDF, JPG, JPEG (até 5MB)
      </div>
    </div>

    @include('site.partials.captcha', ['captcha' => $captcha])

    <button type="submit" class="btn btn-primary">
      Abrir chamado
    </button>
  </form>
</div>

@include('site.partials.footer')
@include('site.partials.scripts')