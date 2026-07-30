<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products - Inventory System</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

  <!-- Navbar -->
  <div id="navbar"></div>

  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar -->
      <div id="sidebar" class="col-md-2"></div>

      <!-- Main Content -->
      <main class="col-md-10 p-4">
        <h1 class="mb-4">Products</h1>
            <p class="text-muted">Manage products, stock quantities, prices, and categories.</p>
        <!-- Add Product Form -->
        <form action="actions/product_create.php" method="POST" class="mb-4">
          <div class="row g-3">

            <div class="col-md-4">
              <input type="text"
                     name="product_name"
                     class="form-control"
                     placeholder="Product Name"
                     required>
            </div>

            <div class="col-md-3">
              <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>

                <?php
                  $cat_sql = "SELECT * FROM categories ORDER BY category_name ASC";
                  $cat_result = mysqli_query($conn, $cat_sql);

                  while ($cat = mysqli_fetch_assoc($cat_result)) {
                ?>
                    <option value="<?php echo $cat['category_id']; ?>">
                      <?php echo $cat['category_name']; ?>
                    </option>
                <?php
                  }
                ?>
              </select>
            </div>

            <div class="col-md-2">
              <input type="number"
                     name="stock_quantity"
                     class="form-control"
                     placeholder="Quantity"
                     required>
            </div>

            <div class="col-md-2">
              <input type="number"
                     step="0.01"
                     name="price"
                     class="form-control"
                     placeholder="Price"
                     required>
            </div>

            <div class="col-md-1">
              <button type="submit" class="btn btn-success w-100">
                Add
              </button>
            </div>

          </div>
        </form>

        <!-- Products Table -->
        <table class="table table-striped" id="productsTable">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Category</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php
              $sql = "SELECT products.*, categories.category_name
                      FROM products
                      INNER JOIN categories
                      ON products.category_id = categories.category_id
                      ORDER BY products.product_id DESC";

              $result = mysqli_query($conn, $sql);

              while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                  <td><?php echo $row["product_id"]; ?></td>
                  <td><?php echo $row["product_name"]; ?></td>
                  <td><?php echo $row["category_name"]; ?></td>
                  <td><?php echo $row["stock_quantity"]; ?></td>
                  <td>$<?php echo $row["price"]; ?></td>
                  <td>
                    <a href="product_edit.php?id=<?php echo $row["product_id"]; ?>"
                         class="btn btn-primary btn-sm">
                                    Edit
                    </a>

                    <a href="actions/product_delete.php?id=<?php echo $row["product_id"]; ?>"
                    class="btn btn-danger btn-sm">
                                   Delete
                    </a>
                  </td>
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