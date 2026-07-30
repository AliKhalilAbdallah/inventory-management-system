<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<?php
  $sales_result = mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM sales");
  $sales_row = mysqli_fetch_assoc($sales_result);
  $total_sales = $sales_row["total"] ?? 0;

  $purchases_result = mysqli_query($conn, "SELECT SUM(total_cost) AS total FROM purchases");
  $purchases_row = mysqli_fetch_assoc($purchases_result);
  $total_purchases = $purchases_row["total"] ?? 0;

  $net_profit = $total_sales - $total_purchases;

  $low_stock_sql = "SELECT * FROM products WHERE stock_quantity < 5 ORDER BY stock_quantity ASC";
  $low_stock_result = mysqli_query($conn, $low_stock_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports - Inventory System</title>
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

        <h1 class="mb-2">Reports</h1>
        <p class="text-muted mb-4">Summary of sales, purchases, profit, and low stock products.</p>

        <!-- Summary Cards -->
        <div class="row mb-4">

          <div class="col-md-4">
            <div class="card text-center shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Total Sales</h5>
                <h3>$<?php echo number_format($total_sales, 2); ?></h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card text-center shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Total Purchases</h5>
                <h3>$<?php echo number_format($total_purchases, 2); ?></h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card text-center shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Net Profit</h5>
                <h3>$<?php echo number_format($net_profit, 2); ?></h3>
              </div>
            </div>
          </div>

        </div>

        <!-- Low Stock Alerts -->
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="mb-3">Low Stock Alerts</h3>

            <?php if (mysqli_num_rows($low_stock_result) > 0) { ?>

              <ul class="list-group">
                <?php while ($product = mysqli_fetch_assoc($low_stock_result)) { ?>
                  <li class="list-group-item list-group-item-warning">
                    <?php echo $product["product_name"]; ?>
                    stock is low:
                    <?php echo $product["stock_quantity"]; ?>
                    items remaining.
                  </li>
                <?php } ?>
              </ul>

            <?php } else { ?>

              <p class="text-muted">No low stock products currently.</p>

            <?php } ?>

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