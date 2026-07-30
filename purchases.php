<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchases - Inventory System</title>
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
        <h1 class="mb-2">Purchases</h1>
        <p class="text-muted">Record stock purchases and automatically increase product quantity.</p>

        <!-- Record Purchase Form -->
        <form action="actions/purchase_create.php" method="POST" class="mb-4">
          <div class="row g-3">

            <div class="col-md-3">
              <select name="product_id" class="form-select" required>
                <option value="">Select Product</option>

                <?php
                  $product_sql = "SELECT * FROM products ORDER BY product_name ASC";
                  $product_result = mysqli_query($conn, $product_sql);

                  while ($product = mysqli_fetch_assoc($product_result)) {
                ?>
                    <option value="<?php echo $product['product_id']; ?>">
                      <?php echo $product['product_name']; ?>
                    </option>
                <?php
                  }
                ?>
              </select>
            </div>

            <div class="col-md-2">
              <input type="number"
                     name="quantity"
                     class="form-control"
                     placeholder="Quantity"
                     required>
            </div>

            <div class="col-md-2">
              <input type="number"
                     step="0.01"
                     name="cost"
                     class="form-control"
                     placeholder="Cost"
                     required>
            </div>

            <div class="col-md-3">
              <input type="text"
                     name="supplier_name"
                     class="form-control"
                     placeholder="Supplier Name"
                     required>
            </div>

            <div class="col-md-2">
              <input type="date"
                     name="date"
                     class="form-control"
                     required>
            </div>

            <div class="col-md-12">
              <button type="submit" class="btn btn-success">
                Record Purchase
              </button>
            </div>

          </div>
        </form>

        <!-- Purchases Table -->
        <table class="table table-striped" id="purchasesTable">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Cost</th>
              <th>Supplier</th>
              <th>Date</th>
            </tr>
          </thead>

          <tbody>
            <?php
              $sql = "SELECT purchase_details.*, purchases.date, purchases.supplier_name, products.product_name
                      FROM purchase_details
                      INNER JOIN purchases
                      ON purchase_details.purchase_id = purchases.purchase_id
                      INNER JOIN products
                      ON purchase_details.product_id = products.product_id
                      ORDER BY purchase_details.purchase_detail_id DESC";

              $result = mysqli_query($conn, $sql);

              while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                  <td><?php echo $row["purchase_detail_id"]; ?></td>
                  <td><?php echo $row["product_name"]; ?></td>
                  <td><?php echo $row["quantity"]; ?></td>
                  <td>$<?php echo $row["cost"]; ?></td>
                  <td><?php echo $row["supplier_name"]; ?></td>
                  <td><?php echo $row["date"]; ?></td>
                </tr>
            <?php
              }
            ?>
          </tbody>
        </table>

      </main>
    </div>
  </div>

  <!-- Footer -->
  <div id="footer"></div>

  <script src="assets/js/main.js"></script>
</body>
</html>