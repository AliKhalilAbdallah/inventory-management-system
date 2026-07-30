<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales - Inventory System</title>

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

        <h2 class="mb-2">Sales</h2>
        <p class="text-muted">Select products, add items, and save the sale to update stock automatically.</p>

        <!-- SALE CARD -->
        <div class="card shadow-sm mb-4">
          <div class="card-body">

            <h5 class="mb-3">Create New Sale</h5>

            <div class="row g-3 align-items-end">

              <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" id="saleDate" class="form-control">
              </div>

              <div class="col-md-3">
                <label class="form-label">Product</label>

                <select id="saleProduct" class="form-select">
                  <option value="">Select Product</option>

                  <?php
                    $product_sql = "SELECT * FROM products ORDER BY product_name ASC";
                    $product_result = mysqli_query($conn, $product_sql);

                    while ($product = mysqli_fetch_assoc($product_result)) {
                  ?>
                      <option value="<?php echo $product['product_id']; ?>"
                              data-price="<?php echo $product['price']; ?>">
                        <?php echo $product['product_name']; ?>
                      </option>
                  <?php
                    }
                  ?>
                </select>

              </div>

              <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" id="saleQuantity" class="form-control">
              </div>

              <div class="col-md-2">
                <label class="form-label">Price</label>
                <input type="number" id="salePrice" class="form-control">
              </div>

              <div class="col-md-2">
                <button type="button" id="addToSale" class="btn btn-primary w-100">
                  Add Item
                </button>
              </div>

            </div>

          </div>
        </div>

        <!-- ITEMS TABLE -->
        <div class="card shadow-sm mb-4">
          <div class="card-body">

            <h5 class="mb-3">Sale Items</h5>

            <table class="table table-hover align-middle" id="saleDetailsTable">
              <thead class="table-dark">
                <tr>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>

          </div>
        </div>

        <!-- TOTAL + SAVE -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 id="saleTotal">Total: $0</h5>

          <button type="button" id="saveSale" class="btn btn-success px-4">
            Save Sale
          </button>
        </div>

        <!-- SALES HISTORY -->
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="mb-3">Sales History</h5>

            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Sale ID</th>
                  <th>Date</th>
                  <th>Total Amount</th>
                  <th>User</th>
                </tr>
              </thead>

              <tbody>
                <?php
                  $sales_sql = "SELECT sales.*, users.username
                                FROM sales
                                INNER JOIN users
                                ON sales.user_id = users.user_id
                                ORDER BY sales.sale_id DESC";

                  $sales_result = mysqli_query($conn, $sales_sql);

                  while ($sale = mysqli_fetch_assoc($sales_result)) {
                ?>
                    <tr>
                      <td><?php echo $sale["sale_id"]; ?></td>
                      <td><?php echo $sale["date"]; ?></td>
                      <td>$<?php echo $sale["total_amount"]; ?></td>
                      <td><?php echo $sale["username"]; ?></td>
                    </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>

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