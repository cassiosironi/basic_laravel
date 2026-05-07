@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')
<?php
  // define "hoje" para max do input date
  $today = date('Y-m-d');
?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Atender Chamado #<?php echo $chamado->numero_chamado ? $chamado->numero_chamado : ('#'.$chamado->id); ?></h1>
    <a class="btn btn-outline-secondary text-light" href="{{ route('admin.chamados.index') }}"><i class="bi bi-arrow-return-left"></i> Voltar</a>
  </div>

  <div class="glass-card">
    <div class="card-body">

      <div class="mb-3">
        <label class="form-label">Número</label>
        <input class="form-control" disabled
               value="<?php echo $chamado->numero_chamado ? $chamado->numero_chamado : ('#'.$chamado->id); ?>">
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Título</label>
          <input class="form-control" disabled value="<?php echo $chamado->titulo; ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Tipo</label>
          <input class="form-control" disabled value="<?php echo $chamado->tipo; ?>">
        </div>

        <div class="col-12" style="pointer-events: none; opacity: .8;">
          <label class="form-label">Descrição</label>
            <input type="hidden" name="descricao" value="<?php echo $chamado->descricao; ?>">
            <div class="quill-editor" data-input="descricao" style="height: 220px;"></div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Autor</label>
          <input class="form-control" disabled value="<?php echo $chamado->autor_nome . ' (' . $chamado->autor_login . ')'; ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Data de abertura</label>
          <input class="form-control" disabled value="<?php echo $chamado->data_abertura; ?>">
        </div>

        <?php
        $anexoPath = isset($chamado) ? (string) $chamado->anexo : '';
        $anexoPath = trim($anexoPath);

        $anexoUrl = '';

        // Caso 1: anexos salvos no Storage public: "chamados/arquivo.pdf"
        if ($anexoPath !== '' && strpos($anexoPath, 'chamados/') === 0) {
            $anexoUrl = asset('storage/' . $anexoPath);
        }

        // Caso 2: anexos salvos em public: "anexos/chamados/arquivo.pdf"
        if ($anexoUrl === '' && $anexoPath !== '') {
            $anexoUrl = asset($anexoPath);
        }
            ?>

        <div class="col-12">
            <label class="form-label">Anexo</label>

            <?php if ($anexoPath !== '' && $anexoUrl !== '') { ?>
                <div class="d-flex gap-2 align-items-center">

                <a class="btn btn-sm btn-outline-secondary text-light"
                    target="_blank"
                    href="<?php echo $anexoUrl; ?>" download>
                    <i class="bi bi-download me-1"></i>Baixar
                </a>
                </div>
            <?php } else { ?>
                <div class="text-muted">Sem anexo</div>
            <?php } ?>
        </div>

      </div>

      <hr class="my-4">

       <form action="{{ route('admin.chamados.update', ['id'=>$chamado->id]) }}" method="post" enctype="multipart/form-data">
     
        @csrf

        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select bg-light" required>
              <option value="em andamento" <?php echo $chamado->status==='em andamento'?'selected':''; ?>>Em andamento</option>
              <option value="resolvido" <?php echo $chamado->status==='resolvido'?'selected':''; ?>>Resolvido</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Data de conclusão</label>
            <input type="date" name="data_conclusao" class="form-control"
                   max="<?php echo $today; ?>"
                   value="<?php echo !empty($chamado->data_conclusao) ? substr($chamado->data_conclusao,0,10) : ''; ?>">
            <div class="form-text">Não pode ser maior que hoje.</div>
          </div>

          <div class="col-12">
            <label class="form-label">Orientações</label>
            <input type="hidden" name="orientacoes" value="<?php echo $chamado->orientacoes; ?>">
            <div class="quill-editor" data-input="orientacoes" style="height: 220px;"></div>
          </div>
        </div>

        <div class="mt-3 d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check2-circle me-1"></i>Salvar atendimento
          </button>
          <a class="btn btn-outline-secondary text-light" href="{{ route('admin.chamados.index') }}">Cancelar</a>
        </div>
      </form>

    </div>
  </div>
</div>
</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')