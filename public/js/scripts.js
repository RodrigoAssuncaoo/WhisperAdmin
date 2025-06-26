document.addEventListener('DOMContentLoaded', () => {
  aplicarFiltroGuardado();
  configurarBotoesFiltro();
  configurarTogglesFormulario();
});

/**
 * Aplica o filtro guardado no localStorage (se existir)
 */
function aplicarFiltroGuardado() {
  const filtroGuardado = localStorage.getItem('filtroRole') || 'todos';
  filtrarTabelaPorRole(filtroGuardado);
}

/**
 * Associa eventos de clique aos botões de filtro
 */
function configurarBotoesFiltro() {
  document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const role = btn.dataset.role;
      filtrarTabelaPorRole(role);
    });
  });
}

/**
 * Mostra/esconde o formulário de adicionar (se existir)
 */
function configurarTogglesFormulario() {
  const toggleBtn = document.getElementById('toggle-form-btn');
  const form = document.getElementById('add-user-form');

  if (toggleBtn && form) {
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
}

/**
 * Mostra o formulário de edição para um ID específico
 */
function mostrarEditar(id) {
  const row = document.getElementById(`editar-${id}`);
  if (row) row.style.display = 'table-row';
}

/**
 * Esconde o formulário de edição para um ID específico
 */
function fecharEditar(id) {
  const row = document.getElementById(`editar-${id}`);
  if (row) row.style.display = 'none';
}

/**
 * Valida o formulário de adição (nome, email, contacto, password, role)
 */
function validarFormulario() {
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
    alert("Nome inválido.");
    return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    alert("Email inválido.");
    return false;
  }

  if (!/^\d{9}$/.test(contacto)) {
    alert("Contacto inválido.");
    return false;
  }

  if (password.length < 6) {
    alert("Password fraca.");
    return false;
  }

  return true;
}

/**
 * Filtra as linhas de utilizadores ou entradas por role
 */
function filtrarTabelaPorRole(role) {
  const linhas = document.querySelectorAll('.user-row');
  const botoes = document.querySelectorAll('.filtro-btn');

  linhas.forEach(l => {
    const r = l.dataset.role?.toLowerCase();
    l.style.display = (role === 'todos' || r === role.toLowerCase()) ? '' : 'none';
  });

  botoes.forEach(btn => {
    btn.classList.remove('active');
    if (btn.dataset.role === role.toLowerCase()) btn.classList.add('active');
  });

  localStorage.setItem('filtroRole', role);
}
