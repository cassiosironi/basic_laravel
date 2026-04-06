@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">

  <div class="mb-3">
    <h1 class="h4 mb-1">Logs de login</h1>
    <p class="text-muted mb-0">
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
            <th>Data / Hora</th>
            <th>IP</th>
            <th>User Agent</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($logs as $log)
            <tr>
              <td><?php echo $log->created_at; ?></td>
              <td><?php echo $log->ip; ?></td>
              <td class="small text-muted">
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

  {{ route('admin.usuarios.index') }}Voltar para usuários</a>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')