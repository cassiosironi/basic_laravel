<div class="mb-3">
  <label class="form-label">Imagem lateral (anexar do computador)</label>

  <?php $imgPath = isset($sobre) && $sobre->image ? $sobre->image : 'img/sobre/sobre1.jpg'; ?>
  <?php $imgUrl  = asset($imgPath); ?>

  {{-- Mantém a imagem atual caso o usuário não envie uma nova --}}
  <input type="hidden" name="image_current" value="<?php echo $imgPath; ?>">

  <div class="input-group">
    <input type="file"
           name="image_file"
           id="sobreImageFile"
           class="form-control"
           accept=".jpg,.jpeg,.png,.webp">

    <button type="button"
            class="btn btn-outline-secondary"
            data-bs-toggle="modal"
            data-bs-target="#modalSobreImagem"
            title="Visualizar imagem">
            <i class="bi bi-eye"></i> Visualizar
    </button>
  </div>

  <div class="form-text">
    Extensões permitidas: <code>.jpg .jpeg .png .webp</code>.
    O arquivo será salvo em <code>public/img/sobre/</code> e o banco armazenará algo como <code>img/sobre/arquivo.jpg</code>.
  </div>

  {{-- mini preview opcional --}}
  <div class="mt-2">
    <img id="sobreImageThumb"
         src="<?php echo $imgUrl; ?>"
         alt="Miniatura"
         style="max-height:70px;border:1px solid #ddd;border-radius:6px;">
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Título</label>
  <input type="text" name="title" class="form-control"
         value="<?php echo isset($sobre) ? $sobre->title : ''; ?>" required maxlength="120">
</div>

<div class="mb-3">
  <label class="form-label">Texto</label>
  <textarea name="text" class="form-control" rows="5" maxlength="5000"><?php echo isset($sobre) ? $sobre->text : ''; ?></textarea>
</div>

<div class="d-flex gap-2">
  <button type="submit" class="btn btn-primary">Salvar</button>
  <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Voltar</a>
</div>

{{-- MODAL Bootstrap --}}
<div class="modal fade" id="modalSobreImagem" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Visualizar imagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="sobreImagePreview"
             src="<?php echo $imgUrl; ?>"
             alt="Pré-visualização"
             style="max-width:100%;height:auto;border-radius:10px;">
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  const fileInput = document.getElementById('sobreImageFile');
  const preview   = document.getElementById('sobreImagePreview');
  const thumb     = document.getElementById('sobreImageThumb');

  if (!fileInput || !preview) return;

  fileInput.addEventListener('change', function () {
    const file = this.files && this.files[0] ? this.files[0] : null;

    if (!file) return;

    // Preview instantâneo (sem upload)
    const url = URL.createObjectURL(file);
    preview.src = url;
    if (thumb) thumb.src = url;
  });
})();
</script>