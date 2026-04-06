@include('admin.partials.head')

@include('admin.partials.notify')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">


      <div class="card shadow-sm mt-5">
        <div class="card-body p-4">

          <h1 class="h4 mb-3 text-center">Login - Admin CMS</h1>

          {{ route('admin.login.submit') }}
            @csrf

            <div class="mb-3">
              <label class="form-label">Login</label>
              <input type="text" name="login" class="form-control"
                     value="<?php echo old('login'); ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Senha</label>
              <input type="password" name="senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              Entrar
            </button>

          </form>

        </div>
      </div>

      <div class="text-center text-muted small mt-3">
        <?php echo 'Acesso restrito'; ?>
      </div>

    </div>
  </div>
</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')