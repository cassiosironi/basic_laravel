<div class="mb-3">
  <label class="form-label">Imagem lateral (caminho em public/)</label>
  <input type="text" name="image" class="form-control"
         value="<?php echo isset($sobre) ? $sobre->image : 'img/sobre/sobre1.jpg'; ?>">
  <div class="form-text">
    Ex: <code>img/sobre/sobre1.jpg</code> será renderizado via <code>asset(...)</code>
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Título</label>
  <input type="text" name="title" class="form-control"
         value="<?php echo isset($sobre) ? $sobre->title : ''; ?>">
</div>

<div class="mb-3">
  <label class="form-label">Texto</label>
  <input type="text" name="subtitle" class="form-control"
         value="<?php echo isset($sobre) ? $sobre->text : ''; ?>">
</div>

<div class="d-flex gap-2">
  <button type="submit" class="btn btn-primary">Salvar</button>
  <a href="{{ route('admin.sobre') }}" class="btn btn-outline-secondary">Voltar</a>
</div>