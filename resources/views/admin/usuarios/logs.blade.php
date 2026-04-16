@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')

  <div class="glass-card">
    <div class="card-body">

      <div class="mb-3">
        <h1 class="h4 mb-1">Logs de login</h1>
        <p class="text-light mb-0">
          Usuário:
          <strong><?php echo $usuario->nome; ?></strong>
          (<?php echo $usuario->login; ?>)
        </p>
      </div>

        @if (count($logs) > 0)
          <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead>
                <tr>
                  <th class="text-light">Data / Hora</th>
                  <th class="text-light">IP</th>
                  <th class="text-light">User Agent</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($logs as $log)
                  <tr>
                    <td class="text-light"><?php echo $log->created_at; ?></td>
                    <td class="text-light"><?php echo $log->ip; ?></td>
                    <td class="small text-light">
                      <?php echo $log->user_agent; ?>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="alert alert-warning">
            <?php echo 'Nenhum log de login encontrado para este usuário.'; ?>
          </div>
        @endif
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-light mt-5"> <i class="bi bi-arrow-return-left"></i> Voltar</a>

    </div>
  </div>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')