<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<?php
  $products_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))["total"];
  $categories_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories"))["total"];
  $sales_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM sales"))["total"];
  $purchases_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM purchases"))["total"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Inventory System</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

  <!-- Navbar -->
  <div id="navbar"></div>

  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar -->
      <div id="sidebar" class="col-md-2 "></div>

      <!-- Main Content -->
      <main class="col-md-10 p-4">

        <h1 class="mb-2">Dashboard</h1>
        <p class="text-muted mb-4">Overview of inventory, sales, purchases, and categories.</p>

        <div class="row g-4">

          <!-- Products Card -->
          <div class="col-md-3">
            <div class="card text-center shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Products</h5>
                <h2><?php echo $products_count; ?></h2>
                <p class="card-text">Total products</p>
                <a href="products.php" class="btn btn-primary">View</a>
              </div>
            </div>
          </div>

          <!-- Categories Card -->
          <div class="col-md-3">
            <div class="card text-center shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <h2><?php echo $categories_count; ?></h2>
                <p class="card-text">Total categories</p>
                <a href="categories.php" class="btn btn-primary">View</a>
              </div>
            </div>
          </div>

          <!-- Sales Card -->
          <div class="col-md-3">
            <div class="card text-center shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Sales</h5>
                <h2><?php echo $sales_count; ?></h2>
                <p class="card-text">Total sales</p>
                <a href="sales.php" class="btn btn-primary">View</a>
              </div>
            </div>
          </div>

          <!-- Purchases Card -->
          <div class="col-md-3">
            <div class="card text-center shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Purchases</h5>
                <h2><?php echo $purchases_count; ?></h2>
                <p class="card-text">Total purchases</p>
                <a href="purchases.php" class="btn btn-primary">View</a>
              </div>
            </div>
          </div>

        </div>

      </main>
    </div>
  </div>

  <!-- Footer -->
  <div id="footer"></div>

  <script src="assets/js/main.js"></script>
</body>
</html>