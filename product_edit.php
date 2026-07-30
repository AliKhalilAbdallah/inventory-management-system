<?php include "config/auth.php"; ?>
<?php include "config/database.php"; ?>

<?php
$id = $_GET["id"];

$product_sql = "SELECT * FROM products WHERE product_id = $id";
$product_result = mysqli_query($conn, $product_sql);
$product = mysqli_fetch_assoc($product_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product - Inventory System</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div id="navbar"></div>

<div class="container-fluid">
  <div class="row">

    <div id="sidebar" class="col-md-2 bg-light"></div>

    <main class="col-md-10 p-4">
      <h1 class="mb-4">Edit Product</h1>

      <div class="card shadow-sm">
        <div class="card-body">

          <form action="actions/product_update.php" method="POST">

            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

            <div class="mb-3">
              <label class="form-label">Product Name</label>
              <input type="text"
                     name="product_name"
                     class="form-control"
                     value="<?php echo $product['product_name']; ?>"
                     required>
            </div>

            <div class="mb-3">
              <label class="form-label">Category</label>
              <select name="category_id" class="form-select" required>

                <?php
                  $cat_sql = "SELECT * FROM categories ORDER BY category_name ASC";
                  $cat_result = mysqli_query($conn, $cat_sql);

                  while ($cat = mysqli_fetch_assoc($cat_result)) {
                ?>
                    <option value="<?php echo $cat['category_id']; ?>"
                      <?php if ($cat['category_id'] == $product['category_id']) echo "selected"; ?>>
                      <?php echo $cat['category_name']; ?>
                    </option>
                <?php
                  }
                ?>

              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Quantity</label>
              <input type="number"
                     name="stock_quantity"
                     class="form-control"
                     value="<?php echo $product['stock_quantity']; ?>"
                     required>
            </div>

            <div class="mb-3">
              <label class="form-label">Price</label>
              <input type="number"
                     step="0.01"
                     name="price"
                     class="form-control"
                     value="<?php echo $product['price']; ?>"
                     required>
            </div>

            <button type="submit" class="btn btn-success">Update Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>

          </form>

        </div>
      </div>

    </main>
  </div>
</div>

<div id="footer"></div>

<script src="assets/js/main.js"></script>
</body>
</html>