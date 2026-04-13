
// datatable trigger 
document.addEventListener('DOMContentLoaded', function () {
    if (typeof $ !== 'undefined' && $('#datatable').length) {
        $('#datatable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            order: [],
            pageLength: 10,
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                paginate: {
                    next: "Próximo",
                    previous: "Anterior"
                },
                zeroRecords: "Nenhum registro encontrado"
            }
        });
    }
});

// validation inputs 
(() => {
  'use strict'

  // Seleciona todos os forms com .needs-validation
  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()


// preloader 
(function () {

  const preloader = document.getElementById('admin-preloader');

  if (!preloader) return;

  function showPreloader() {
    preloader.classList.remove('d-none');
    document.body.classList.add('loading');
  }

  // SUBMIT de QUALQUER FORM do admin
  document.addEventListener('submit', function (e) {
    const form = e.target;

    // só forms do admin (opcional)
    if (form.tagName === 'FORM') {
      showPreloader();
    }
  }, true);

  // CLIQUE EM LINKS DE AÇÃO (ex: delete, navegação)
  document.addEventListener('click', function (e) {
    const el = e.target.closest('a');

    if (!el) return;

    // ignora anchors, javascript:void, target blank
    const href = el.getAttribute('href');

    if (!href || href.startsWith('#') || href.startsWith('javascript')) {
      return;
    }

    // ignora links que abrem em nova aba
    if (el.getAttribute('target') === '_blank') {
      return;
    }

    showPreloader();
  });

})();
