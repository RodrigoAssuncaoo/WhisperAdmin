<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar.php';
require_once '../../backend/db.php';

// Quantidade de utilizadores
$quantidadeUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Quantidade de roteiros
$quantidadeRoteiros = $pdo->query("SELECT COUNT(*) FROM roteiros")->fetchColumn();

// Avaliações boas (>5)
$avaliacoesBoas = $pdo->query("SELECT COUNT(*) FROM avaliacoes WHERE avaliacao_roteiro > 5")->fetchColumn();

// Top 5 roteiros mais populares (mais comprados)
$topRoteiros = $pdo->query("
    SELECT r.nome, COUNT(rc.id) AS total_compras
    FROM roteiro_compras rc
    INNER JOIN roteiros r ON rc.id_roteiro = r.id
    GROUP BY rc.id_roteiro
    ORDER BY total_compras DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div>

  <section class="section dashboard">
    <div class="row">
      <!-- Roteiros -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card">
          <div class="card-body">
            <h5 class="card-title">Total de Roteiros</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-map"></i>
              </div>
              <div class="ps-3">
                <h6><?= $quantidadeRoteiros ?></h6>
                <span class="text-muted small pt-2 ps-1">Roteiros criados</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Avaliações boas -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card customers-card">
          <div class="card-body">
            <h5 class="card-title">Avaliações Positivas</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-star-fill"></i>
              </div>
              <div class="ps-3">
                <h6><?= $avaliacoesBoas ?></h6>
                <span class="text-success small pt-1 fw-bold">>5 estrelas</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Utilizadores -->
      <div class="col-xxl-4 col-xl-12">
        <div class="card info-card customers-card">
          <div class="card-body">
            <h5 class="card-title">Utilizadores</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-people"></i>
              </div>
              <div class="ps-3">
                <h6><?= $quantidadeUsers ?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabela de roteiros mais populares -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Top 5 Roteiros Mais Populares</h5>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Roteiro</th>
                  <th>Total de Compras</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($topRoteiros as $roteiro): ?>
                  <tr>
                    <td><?= htmlspecialchars($roteiro['nome']) ?></td>
                    <td><?= $roteiro['total_compras'] ?></td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($topRoteiros)): ?>
                  <tr><td colspan="2">Nenhum dado encontrado.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php 
include 'includes/footer.php';
include 'includes/script.php';
?>
