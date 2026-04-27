<div class="row g-2 align-items-end">

  {{-- Honeypot (campo invisível para humanos) --}}
  <div style="position:absolute;left:-9999px;top:-9999px;height:0;overflow:hidden;">
    <label>Website</label>
    <input type="text" name="website" value="">
  </div>

  <div class="col-12 col-md-6">
    <label class="form-label">Verificação (anti-robô)</label>
    <div class="input-group">
      <span class="input-group-text">
        <?php echo $captcha['a']; ?> + <?php echo $captcha['b']; ?> =
      </span>
      <input type="text" name="captcha" class="form-control" required inputmode="numeric">
    </div>
    <div class="form-text">Digite o resultado da soma.</div>
  </div>

</div>