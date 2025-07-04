<?php
session_start();
include 'includes/isAdmin.php';
include 'includes/header.php';
include 'includes/sidebar.php';
require_once '../../backend/db.php';

// Total users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Total routes
$totalRoutes = $pdo->query("SELECT COUNT(*) FROM roteiros")->fetchColumn();

// Good reviews (>5)
$positiveReviews = $pdo->query("SELECT COUNT(*) FROM avaliacoes WHERE avaliacao_roteiro > 5")->fetchColumn();

// Top 5 most popular routes based on average score
$topRoutes = $pdo->query("
    SELECT r.nome, ROUND(AVG(a.avaliacao_roteiro), 2) AS average_score
    FROM roteiros r
    JOIN avaliacoes a ON r.id = a.id_roteiro
    GROUP BY r.id
    HAVING average_score > 7
    ORDER BY average_score DESC
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

      <!-- Routes -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card">
          <div class="card-body">
            <h5 class="card-title">Total Routes</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-map"></i>
              </div>
              <div class="ps-3">
                <h6><?= $totalRoutes ?></h6>
                <span class="text-muted small pt-2 ps-1">Routes created</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Positive Reviews -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card customers-card">
          <div class="card-body">
            <h5 class="card-title">Positive Reviews</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-star-fill"></i>
              </div>
              <div class="ps-3">
                <h6><?= $positiveReviews ?></h6>
                <span class="text-success small pt-1 fw-bold">>5 stars</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Users -->
      <div class="col-xxl-4 col-xl-12">
        <div class="card info-card customers-card">
          <div class="card-body">
            <h5 class="card-title">Users</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-people"></i>
              </div>
              <div class="ps-3">
                <h6><?= $totalUsers ?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Most Popular Routes Table -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Popular Routes (Average Rating > 7)</h5>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Route</th>
                  <th>Average Rating</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($topRoutes as $route): ?>
                  <tr>
                    <td><?= htmlspecialchars($route['nome']) ?></td>
                    <td><?= $route['average_score'] ?></td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($topRoutes)): ?>
                  <tr><td colspan="2">No data found.</td></tr>
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
