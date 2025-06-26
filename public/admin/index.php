<?php
session_start();
include 'includes/header.php';
include 'includes/sidebar.php';
include '../../backend/db.php';

// Contar utilizadores
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$quantidadeUsers = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM avaliacoes WHERE avaliacao_roteiro > 5");
$avaliacoesBoas = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM roteiros");
$quantidadeRoteiros = $stmt->fetchColumn();
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
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">
      <!-- Card 1 -->
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

      <!-- Card 2 -->
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

      <!-- Card 3 -->
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

      <!-- Exemplo de gráfico -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Gráfico de Exemplo</h5>
            <div id="graficoDashboard"></div>
            <script>
              document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#graficoDashboard"), {
                  series: [{
                    name: "Utilizadores",
                    data: [10, 20, 15, 30, 25, 35, 40]
                  }],
                  chart: {
                    height: 350,
                    type: 'line',
                    toolbar: { show: false }
                  },
                  stroke: { curve: 'smooth' },
                  xaxis: {
                    categories: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul"]
                  }
                }).render();
              });
            </script>
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
