<div class="mb-3">
  <label class="form-label">Imagem banner</label>

  <?php $imgPath = isset($banner) && $banner->image ? $banner->image : 'img/banners/default.png'; ?>
  <?php $imgUrl  = asset($imgPath); ?>

  {{-- Mantém a imagem atual caso o usuário não envie uma nova --}}
  <input type="hidden" name="image_current" value="<?php echo $imgPath; ?>">

  <div class="input-group">
    <input type="file"
           name="image_file"
           id="bannerImageFile"
           class="form-control"
           accept=".jpg,.jpeg,.png,.webp">
    @if (!empty($imgUrl))
    <button type="button"
            class="btn btn-outline-secondary"
            data-bs-toggle="modal"
            data-bs-target="#modalBannerImagem"
            title="Visualizar imagem">
            <i class="bi bi-eye"></i> Visualizar
    </button>
    @endif
  </div>

  <div class="form-text">
    Extensões permitidas: <code>.jpg .jpeg .png .webp</code>.
    O arquivo será salvo em <code>public/img/banners/</code> e o banco armazenará algo como <code>img/banners/arquivo.jpg</code>.
  </div>

  @if (!empty($imgUrl))
  {{-- mini preview opcional --}}
  <div class="mt-2">
    <img id="bannerImageThumb"
         src="<?php echo $imgUrl; ?>"
         alt="Miniatura"
         style="max-height:70px;border:1px solid #ddd;border-radius:6px;" 
         loading="lazy">
  </div>
  @endif
</div>


<div class="mb-3">
  <label class="form-label">Título</label>
  <input type="text" name="title" class="form-control"
         value="<?php echo isset($banner) ? $banner->title : ''; ?>">
</div>

<div class="mb-3">
  <label class="form-label">Subtexto</label>
  <input type="text" name="subtitle" class="form-control"
         value="<?php echo isset($banner) ? $banner->subtitle : ''; ?>">
</div>

<div class="d-flex gap-2">
  <button type="submit" class="btn btn-primary">Salvar</button>
  <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Voltar</a>
</div>



{{-- MODAL Bootstrap --}}
<div class="modal fade" id="modalBannerImagem" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Visualizar imagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body text-center">
        <img id="bannerImagePreview"
             src="<?php echo $imgUrl; ?>"
             alt="Pré-visualização"
             style="max-width:100%;height:auto;border-radius:10px;"
             loading="lazy">
      </div>
    </div>
  </div>
</div>


<script>
(function () {
  const fileInput = document.getElementById('bannerImageFile');
  const preview   = document.getElementById('bannerImagePreview');
  const thumb     = document.getElementById('bannerImageThumb');

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