document.addEventListener('DOMContentLoaded', function () {

  const editors = document.querySelectorAll('.quill-editor');

  editors.forEach(function (el) {

    const inputName = el.getAttribute('data-input');
    const hiddenInput = document.querySelector('input[name="' + inputName + '"]');

    if (!hiddenInput) return;

    const quill = new Quill(el, {
      theme: 'snow',
      placeholder: 'Digite o conteúdo...',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link'],
          ['clean']
        ]
      }
    });

    // Carrega conteúdo inicial
    if (hiddenInput.value) {
      quill.root.innerHTML = hiddenInput.value;
    }

    // Sincroniza com input hidden
    quill.on('text-change', function () {
      hiddenInput.value = quill.root.innerHTML;
    });
  });

});
