/**
 * main.js
 * Combina o NiceAdmin template + tuas customizações
 */
(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach(e => e.addEventListener(type, listener))
    } else {
      select(el, all).addEventListener(type, listener)
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Sidebar toggle
   */
  if (select('.toggle-sidebar-btn')) {
    on('click', '.toggle-sidebar-btn', function(e) {
      select('body').classList.toggle('toggle-sidebar')
    })
  }

  /**
   * Search bar toggle
   */
  if (select('.search-bar-toggle')) {
    on('click', '.search-bar-toggle', function(e) {
      select('.search-bar').classList.toggle('search-bar-show')
    })
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Initiate tooltips
   */
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  /**
   * Initiate quill editors
   */
  if (select('.quill-editor-default')) {
    new Quill('.quill-editor-default', {
      theme: 'snow'
    });
  }
  if (select('.quill-editor-bubble')) {
    new Quill('.quill-editor-bubble', {
      theme: 'bubble'
    });
  }
  if (select('.quill-editor-full')) {
    new Quill(".quill-editor-full", {
      modules: {
        toolbar: [
          [{ font: [] }, { size: [] }],
          ["bold", "italic", "underline", "strike"],
          [{ color: [] }, { background: [] }],
          [{ script: "super" }, { script: "sub" }],
          [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
          ["direction", { align: [] }],
          ["link", "image", "video"],
          ["clean"]
        ]
      },
      theme: "snow"
    });
  }

  /**
   * Initiate TinyMCE Editor
   */
  const useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help quickbars emoticons accordion',
    menubar: 'file edit view insert format tools table help',
    toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | emoticons | code fullscreen preview | save print | pagebreak codesample | ltr rtl",
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_prefix: '{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '2m',
    image_advtab: true,
    importcss_append: true,
    height: 600,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_class: 'mceNonEditable',
    toolbar_mode: 'sliding',
    contextmenu: 'link image table',
    skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_css: useDarkMode ? 'dark' : 'default',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
  });

  /**
   * Bootstrap validation check
   */
  var needsValidation = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(needsValidation).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })

  /**
   * Initiate Datatables
   */
  const datatables = select('.datatable', true) || []
  datatables.forEach(datatable => {
    new simpleDatatables.DataTable(datatable, {
      perPageSelect: [5, 10, 15, ["All", -1]],
      columns: [
        { select: 2, sortSequence: ["desc", "asc"] },
        { select: 3, sortSequence: ["desc"] },
        { select: 4, cellClass: "green", headerClass: "red" }
      ]
    });
  })

  /**
   * Autoresize echart charts
   */
  const mainContainer = select('#main');
  if (mainContainer) {
    setTimeout(() => {
      new ResizeObserver(() => {
        select('.echart', true).forEach(chart => {
          echarts.getInstanceByDom(chart).resize();
        })
      }).observe(mainContainer);
    }, 200);
  }

  /***************************************************************************
   *  A PARTIR DESTE PONTO, AS TUAS FUNÇÕES CUSTOMIZADAS (filter, form toggle)
   ***************************************************************************/

  // Aplica o filtro guardado (localStorage)
  function aplicarFiltroGuardado() {
    const filtroGuardado = localStorage.getItem('filtroRole') || 'todos';
    filtrarTabelaPorRole(filtroGuardado);
  }

  // Configura botões de filtro
  function configurarBotoesFiltro() {
    document.querySelectorAll('.filtro-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const role = btn.dataset.role;
        filtrarTabelaPorRole(role);
      });
    });
  }

  // Toggle do formulário de adicionar
  function configurarTogglesFormulario() {
    const toggleBtn = document.getElementById('toggle-form-btn');
    const form = document.getElementById('add-user-form');
    if (!toggleBtn || !form) return;

    toggleBtn.addEventListener('click', () => {
      const isHidden = form.style.display === 'none' || form.style.display === '';
      if (isHidden) {
        form.style.display = 'block';
        form.classList.remove('animate__fadeOutUp');
        form.classList.add('animate__fadeInDown');
      } else {
        form.classList.remove('animate__fadeInDown');
        form.classList.add('animate__fadeOutUp');
        setTimeout(() => form.style.display = 'none', 500);
      }
    });
  }

  // Mostra o form de edição na linha correspondente
  window.mostrarEditar = function(id) {
    const row = document.getElementById(`editar-${id}`);
    if (row) row.style.display = 'table-row';
  }

  // Fecha o form de edição
  window.fecharEditar = function(id) {
    const row = document.getElementById(`editar-${id}`);
    if (row) row.style.display = 'none';
  }

  // Validação do form de adicionar/editar
  window.validarFormulario = function() {
    const nome = document.getElementById("nome")?.value.trim();
    const email = document.getElementById("email")?.value.trim();
    const contacto = document.getElementById("contacto")?.value.trim();
    const password = document.getElementById("password")?.value.trim();
    const role = document.getElementById("role")?.value;

    if (!nome || !email || !contacto || !password || !role) {
      alert("Todos os campos são obrigatórios.");
      return false;
    }
    if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nome)) {
      alert("Nome inválido."); return false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      alert("Email inválido."); return false;
    }
    if (!/^\d{9}$/.test(contacto)) {
      alert("Contacto inválido."); return false;
    }
    if (password.length < 6) {
      alert("Password fraca."); return false;
    }
    return true;
  }

  // Filtra linhas com base no data-role
  function filtrarTabelaPorRole(role) {
    const linhas = document.querySelectorAll('.user-row');
    const botoes = document.querySelectorAll('.filtro-btn');
    linhas.forEach(l => {
      const r = l.dataset.role?.toLowerCase();
      l.style.display = (role === 'todos' || r === role.toLowerCase()) ? '' : 'none';
    });
    botoes.forEach(btn => {
      btn.classList.toggle('active', btn.dataset.role === role.toLowerCase());
    });
    localStorage.setItem('filtroRole', role);
  }

  // Quando a página carrega, inicializa filtros e toggles
  window.addEventListener('DOMContentLoaded', () => {
    aplicarFiltroGuardado();
    configurarBotoesFiltro();
    configurarTogglesFormulario();
  });

})();
