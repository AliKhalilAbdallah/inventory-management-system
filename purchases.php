<?php

include "config/auth.php";
include "config/database.php";

$success_message = "";
$error_message = "";

if (
    isset($_GET["success"]) &&
    $_GET["success"] === "purchase_created"
) {
    $success_message =
        "Purchase recorded successfully and product stock was updated.";
}

if (isset($_GET["error"])) {
    switch ($_GET["error"]) {
        case "invalid_product":
            $error_message = "Please select a valid product.";
            break;

        case "product_not_found":
            $error_message = "The selected product no longer exists.";
            break;

        case "invalid_quantity":
            $error_message = "Purchase quantity must be greater than zero.";
            break;

        case "invalid_cost":
            $error_message = "Unit cost must be greater than zero.";
            break;

        case "invalid_supplier":
            $error_message = "Supplier name cannot be empty.";
            break;

        case "supplier_too_long":
            $error_message =
                "Supplier name must not exceed 100 characters.";
            break;

        case "invalid_date":
            $error_message = "Please select a valid purchase date.";
            break;

        default:
            $error_message =
                "The purchase could not be recorded. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">

  <title>Purchases - Inventory System</title>

  <link
    rel="stylesheet"
    href="assets/css/style.css">

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

        <h1 class="mb-2">Purchases</h1>

        <p class="text-muted">
          Record stock purchases and automatically increase product quantity.
        </p>

        <?php if ($success_message): ?>
          <div
            class="alert alert-success alert-dismissible fade show"
            role="alert">

            <?php echo htmlspecialchars($success_message); ?>

            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Close">
            </button>
          </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
          <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert">

            <?php echo htmlspecialchars($error_message); ?>

            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Close">
            </button>
          </div>
        <?php endif; ?>

        <!-- Record Purchase Form -->
        <form
          action="actions/purchase_create.php"
          method="POST"
          class="mb-4">

          <div class="row g-3">

            <div class="col-md-3">
              <select
                name="product_id"
                class="form-select"
                required>

                <option value="">Select Product</option>

                <?php
                  $product_sql =
                      "SELECT product_id, product_name
                       FROM products
                       ORDER BY product_name ASC";

                  $product_result =
                      mysqli_query($conn, $product_sql);

                  while (
                      $product =
                      mysqli_fetch_assoc($product_result)
                  ) {
                ?>
                    <option
                      value="<?php echo $product["product_id"]; ?>">

                      <?php
                        echo htmlspecialchars(
                            $product["product_name"]
                        );
                      ?>
                    </option>
                <?php
                  }
                ?>
              </select>
            </div>

            <div class="col-md-2">
              <input
                type="number"
                name="quantity"
                class="form-control"
                placeholder="Quantity"
                min="1"
                step="1"
                required>
            </div>

            <div class="col-md-2">
              <input
                type="number"
                name="cost"
                class="form-control"
                placeholder="Unit Cost"
                min="0.01"
                step="0.01"
                required>
            </div>

            <div class="col-md-3">
              <input
                type="text"
                name="supplier_name"
                class="form-control"
                placeholder="Supplier Name"
                maxlength="100"
                required>
            </div>

            <div class="col-md-2">
              <input
                type="date"
                name="date"
                class="form-control"
                required>
            </div>

            <div class="col-md-12">
              <button
                type="submit"
                class="btn btn-success">

                Record Purchase
              </button>
            </div>

          </div>
        </form>

        <!-- Purchases Table -->
        <table
          class="table table-striped"
          id="purchasesTable">

          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Unit Cost</th>
              <th>Total Cost</th>
              <th>Supplier</th>
              <th>Date</th>
            </tr>
          </thead>

          <tbody>

            <?php
              $sql =
                  "SELECT
                      purchase_details.purchase_detail_id,
                      purchase_details.quantity,
                      purchase_details.cost,
                      purchases.date,
                      purchases.supplier_name,
                      products.product_name
                   FROM purchase_details
                   INNER JOIN purchases
                     ON purchase_details.purchase_id =
                        purchases.purchase_id
                   INNER JOIN products
                     ON purchase_details.product_id =
                        products.product_id
                   ORDER BY
                     purchase_details.purchase_detail_id DESC";

              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) === 0) {
            ?>
                <tr>
                  <td
                    colspan="7"
                    class="text-center text-muted py-4">

                    No purchase records are available.
                  </td>
                </tr>
            <?php
              }

              while ($row = mysqli_fetch_assoc($result)) {
                  $line_total =
                      (float) $row["quantity"] *
                      (float) $row["cost"];
            ?>

                <tr>
                  <td>
                    <?php
                      echo $row["purchase_detail_id"];
                    ?>
                  </td>

                  <td>
                    <?php
                      echo htmlspecialchars(
                          $row["product_name"]
                      );
                    ?>
                  </td>

                  <td>
                    <?php echo $row["quantity"]; ?>
                  </td>

                  <td>
                    $<?php
                      echo number_format(
                          (float) $row["cost"],
                          2
                      );
                    ?>
                  </td>

                  <td>
                    $<?php
                      echo number_format(
                          $line_total,
                          2
                      );
                    ?>
                  </td>

                  <td>
                    <?php
                      echo htmlspecialchars(
                          $row["supplier_name"]
                      );
                    ?>
                  </td>

                  <td>
                    <?php echo $row["date"]; ?>
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

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
  </script>

  <script src="assets/js/main.js"></script>

</body>
</html>