<?php
// Obter o nome da página atual (ex: "userstable.php")
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- DASHBOARD -->
    <li class="nav-item">
      <a class="nav-link <?= $currentPage === 'index.php' ? '' : 'collapsed' ?>" href="/admin/index.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- TABELAS -->
    <li class="nav-item">
      <a class="nav-link <?= in_array($currentPage, [
          'userstable.php',
          'roteirostable.php',
          'pontostable.php',
          'avaliacoestable.php'
        ]) ? '' : 'collapsed' ?>"
        data-bs-toggle="collapse"
        href="#tables-nav"
      >
        <i class="bi bi-layout-text-window-reverse"></i>
        <span>Tabelas</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="tables-nav"
          class="nav-content collapse <?= in_array($currentPage, [
              'userstable.php',
              'roteirostable.php',
              'pontostable.php',
              'avaliacoestable.php'
            ]) ? 'show' : '' ?>"
          data-bs-parent="#sidebar-nav"
      >
        <li>
          <a href="/admin/tables/userstable.php" class="<?= $currentPage === 'userstable.php' ? 'active' : '' ?>">
            <i class="bi bi-circle"></i><span>Users</span>
          </a>
        </li>
        <li>
          <a href="/admin/tables/roteirostable.php" class="<?= $currentPage === 'roteirostable.php' ? 'active' : '' ?>">
            <i class="bi bi-circle"></i><span>Routes</span>
          </a>
        </li>
        <li>
          <a href="/admin/tables/avaliacoestable.php" class="<?= $currentPage === 'avaliacoestable.php' ? 'active' : '' ?>">
            <i class="bi bi-circle"></i><span>Avaliations</span>
          </a>
        </li>
        <li>
          <a href="/admin/tables/pontostable.php" class="<?= $currentPage === 'pontostable.php' ? 'active' : '' ?>">
            <i class="bi bi-circle"></i><span>Points</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>
