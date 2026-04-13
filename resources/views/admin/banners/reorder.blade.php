@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Reordenar Banners</h1>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">  
        Voltar
        </a>
  </div>

  <div class="alert alert-info">
    Arraste os banners pelo ícone “≡” para definir a nova ordem. Depois clique em <strong>Salvar ordem</strong>.
  </div>

  <div class="card shadow-sm">
    <div class="card-body">

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width: 60px;">Ordem</th>
              <th style="width: 80px;"></th>
              <th>Título</th>
              <th class="text-muted">Subtexto</th>
            </tr>
          </thead>

          <tbody id="sortable-banners">
            @foreach ($banners as $b)
              <tr data-id="<?php echo $b->id; ?>">
                <td class="handle" style="cursor: grab;">
                  <span class="badge text-bg-secondary">≡</span>
                </td>
                <td>
                  <img src="{{ asset($b->image) }}" style="max-height:40px;border-radius:6px;" loading="lazy">
                </td>
                <td><?php echo $b->title; ?></td>
                <td class="text-muted"><?php echo $b->subtitle; ?></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button type="button" id="btnSaveOrder" class="btn btn-primary">
          Salvar ordem
        </button>
      </div>

    </div>
  </div>
</div>

{{-- SortableJS via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
(function () {
  const tbody = document.getElementById('sortable-banners');
  const btn = document.getElementById('btnSaveOrder');

  if (!tbody || !btn) return;

  // Ativa drag & drop
  new Sortable(tbody, {
    animation: 150,
    handle: '.handle'
  });

  async function saveOrder() {
    const ids = Array.from(tbody.querySelectorAll('tr'))
      .map(tr => parseInt(tr.getAttribute('data-id'), 10))
      .filter(n => !isNaN(n));

    // Envia via fetch (AJAX) para salvar
    const url = "<?php echo route('admin.banners.reorder.save'); ?>";
    const token = "<?php echo csrf_token(); ?>";

    // (Opcional) se você tem preloader global, pode disparar aqui.
    // document.getElementById('admin-preloader')?.classList.remove('d-none');

    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ order: ids })
    });

    if (!res.ok) {
      const txt = await res.text();
      alert('Erro ao salvar ordem: ' + txt);
      return;
    }

    // Após salvar, redireciona para index (para ver a ordem)
    window.location.href = "<?php echo route('admin.banners.index'); ?>";
  }

  btn.addEventListener('click', function () {
    saveOrder().catch(err => alert(err.message));
  });
})();
</script>

@include('admin.partials.footer')
@include('admin.partials.scripts')