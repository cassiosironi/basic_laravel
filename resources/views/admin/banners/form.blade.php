<div class="mb-3">
  <label class="form-label">Imagem (caminho em public/)</label>
  <input type="text" name="image" class="form-control"
         value="<?php echo isset($banner) ? $banner->image : 'img/banners/banner1.jpg'; ?>" required>
  <div class="form-text">
    Ex: <code>img/banners/banner1.jpg</code> (será renderizado via <code>asset(...)</code>
  </div>
    <div class="invalid-feedback">
          Campo obrigatório!
    </div>
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