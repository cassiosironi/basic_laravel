@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Chamados</h1>
  </div>

  {{-- filtro simples opcional --}}
    <div class="row g-2 align-items-end mb-3">
      <div class="col-md-4">
        <label class="form-label">Filtrar por Status</label>
        <select name="status" class="bg-light form-select">
          <option value="" <?php echo $statusFiltro===''?'selected':''; ?>>Todos</option>
          <option value="em andamento" <?php echo $statusFiltro==='em andamento'?'selected':''; ?>>Em andamento</option>
          <option value="resolvido" <?php echo $statusFiltro==='resolvido'?'selected':''; ?>>Resolvido</option>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a class="btn btn-outline-secondary" href="{{ route('admin.chamados.index') }}">Limpar</a>
      </div>
    </div>
  </form>

  <div class="glass-card">
    <div class="card-body">
      <div class="table-responsive">
        <table id="datatable" class="table table-hover align-middle">
          <thead>
            <tr>
              <th class="text-light">Nº</th>
              <th class="text-light">Título</th>
              <th class="text-light">Tipo</th>
              <th class="text-light">Status</th>
              <th class="text-light">Abertura</th>
              <th class="text-light">Autor</th>
              <th class="text-light text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($chamados as $c)
                <tr>
                    <td class="text-light"><?php echo $c->numero_chamado ? $c->numero_chamado : ('#'.$c->id); ?></td>
                    <td class="text-light"><?php echo $c->titulo; ?></td>
                    <td class="text-light"><?php echo $c->tipo; ?></td>
                    <td>
                    <?php if ($c->status === 'resolvido') { ?>
                        <span class="badge text-bg-success">Resolvido</span>
                    <?php } else { ?>
                        <span class="badge text-bg-warning">Em andamento</span>
                    <?php } ?>
                    </td>
                    <td class="text-light"><?php echo $c->data_abertura; ?></td>
                    <td class="text-light"><?php echo $c->autor_nome . ' (' . $c->autor_login . ')'; ?></td>
                    <td class="text-end">
                        <a class="btn btn-outline-secondary btn-sm text-light" href="/admin/chamados/<?php echo $c->id; ?>/edit">
                        <i class="bi bi-pencil"></i> Responder
                    </a>
                    </td>                
                </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')
