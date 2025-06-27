<?php
  // Obtém o nome do arquivo atual (ex.: "avaliacoestable.php")
  $currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a
        class="nav-link <?= $currentPage === 'index.php' ? '' : 'collapsed' ?>"
        href="/admin/index.php"
      >
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Tabelas -->
    <li class="nav-item">
      <a
        class="nav-link <?= in_array($currentPage, [
            'userstable.php',
            'roteirostable.php',
            'pontostable.php',
            'avaliacoestable.php'   // adicionamos aqui
          ]) ? '' : 'collapsed' ?>"
        data-bs-toggle="collapse"
        href="#tables-nav"
      >
        <i class="bi bi-layout-text-window-reverse"></i>
        <span>Tables</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul
        id="tables-nav"
        class="nav-content collapse <?= in_array($currentPage, [
            'userstable.php',
            'roteirostable.php',
            'pontostable.php',
            'avaliacoestable.php'   // e aqui
          ]) ? 'show' : '' ?>"
        data-bs-parent="#sidebar-nav"
      >
        <li>
          <a
            href="/admin/tables/userstable.php"
            class="<?= $currentPage === 'userstable.php' ? 'active' : '' ?>"
          >
            <i class="bi bi-circle"></i><span>Users Table</span>
          </a>
        </li>
        <li>
          <a
            href="/admin/tables/roteirostable.php"
            class="<?= $currentPage === 'roteirostable.php' ? 'active' : '' ?>"
          >
            <i class="bi bi-circle"></i><span>Routes Table</span>
          </a>
        </li>
        <li>
          <a
            href="/admin/tables/avaliacoestable.php"
            class="<?= $currentPage === 'avaliacoestable.php' ? 'active' : '' ?>"
          >
            <i class="bi bi-circle"></i><span>Avaliações Table</span>
          </a>
        </li>
        <li>
          <a
            href="/admin/tables/pontostable.php"
            class="<?= $currentPage === 'pontostable.php' ? 'active' : '' ?>"
          >
            <i class="bi bi-circle"></i><span>Points Table</span>
          </a>
        </li>
      </ul>
    </li>

  </ul>
</aside>
