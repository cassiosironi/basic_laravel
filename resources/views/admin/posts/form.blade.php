<?php
$coverPath = isset($post) && !empty($post->cover_image) ? $post->cover_image : 'img/posts/default.png';
$coverUrl  = asset($coverPath);
?>

<div class="mb-3">
  <label class="form-label">Título</label>
  <input type="text" name="title" class="form-control" required maxlength="160"
         value="<?php echo isset($post) ? $post->title : old('title'); ?>">
</div>

<div class="mb-3">
  <label class="form-label">Imagem de capa</label>

  <input type="hidden" name="cover_current" value="<?php echo isset($post) ? ($post->cover_image ?? '') : ''; ?>">

  <div class="input-group">
    <input type="file" name="cover_file" class="form-control" accept=".jpg,.jpeg,.png,.webp">

    <button type="button"
            class="btn btn-outline-secondary"
            data-bs-toggle="modal"
            data-bs-target="#modalCover">
      <i class="bi bi-eye"></i>
    </button>
  </div>

  @if (!empty($coverUrl))
  <div class="mt-2">
    <img src="<?php echo $coverUrl; ?>"
         alt="Capa"
         loading="lazy"
         style="max-height:70px;border:1px solid #ddd;border-radius:6px-lg">  
  </div>
  @endif
</div>

<div class="mb-3">
   <label class="form-label">Resumo</label>
          <textarea name="summary" class="form-control" rows="3" required maxlength="500"><?php echo isset($post) ? $post->summary : old('summary'); ?></textarea>
</div>

<div class="mb-3">
  <label class="form-label">Conteúdo</label>

  <input type="hidden" name="content" value="<?php echo isset($post) ? $post->content : old('content'); ?>">
  <div class="quill-editor" data-input="content" style="height: 430px;"></div>

  <div class="form-text">
    Editor visual (HTML). Evite colar conteúdo com estilos excessivos.
  </div>
</div>


{{-- MODAL Bootstrap --}}
<div class="modal fade" id="modalCover" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered ">
    <div class="modal-content bg-light">
      <div class="modal-header">
        <h5 class="modal-title text-dark">Visualizar imagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body text-center">
        <img src="<?php echo $coverUrl; ?>"
             alt="Pré-visualização"
             style="max-width:100%;height:auto;border-radius:10px;"
             loading="lazy">
      </div>
    </div>
  </div>
</div>