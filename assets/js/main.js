console.log("MAIN JS LOADED ✔");

// Load reusable components
function loadComponent(id, file) {
  fetch(file)
    .then(response => response.text())
    .then(data => {
      const element = document.getElementById(id);
      if (element) {
        element.innerHTML = data;
      }
    });
}

window.onload = () => {
  if (document.getElementById("navbar")) {
    loadComponent("navbar", "components/navbar.php");
  }

  if (document.getElementById("sidebar")) {
    loadComponent("sidebar", "components/sidebar.php");
  }

  if (document.getElementById("footer")) {
    loadComponent("footer", "components/footer.php");
  }
};

// Sales logic
let currentSaleItems = [];

const saleProductSelect = document.getElementById("saleProduct");
const salePriceInput = document.getElementById("salePrice");

if (saleProductSelect && salePriceInput) {
  saleProductSelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute("data-price");

    salePriceInput.value = price || "";
  });
}

const addToSaleBtn = document.getElementById("addToSale");

if (addToSaleBtn) {
  addToSaleBtn.addEventListener("click", () => {
    const productSelect = document.getElementById("saleProduct");
    const productId = productSelect.value;
    const productName = productSelect.options[productSelect.selectedIndex].text;
    const quantity = document.getElementById("saleQuantity").value;
    const price = document.getElementById("salePrice").value;

    if (!productId || !quantity || !price) {
      alert("Fill all fields");
      return;
    }

    const subtotal = Number(quantity) * Number(price);

    currentSaleItems.push({
      product_id: productId,
      product_name: productName,
      quantity: Number(quantity),
      price: Number(price),
      subtotal: subtotal
    });

    renderSaleDetails();
  });
}

function renderSaleDetails() {
  const table = document.querySelector("#saleDetailsTable tbody");

  if (!table) return;

  table.innerHTML = "";

  currentSaleItems.forEach((item, index) => {
    table.innerHTML += `
      <tr>
        <td>${item.product_name}</td>
        <td>${item.quantity}</td>
        <td>$${item.subtotal.toFixed(2)}</td>
        <td>
          <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">X</button>
        </td>
      </tr>
    `;
  });

  updateSaleTotal();
}

function removeItem(index) {
  currentSaleItems.splice(index, 1);
  renderSaleDetails();
}

function updateSaleTotal() {
  const total = currentSaleItems.reduce((sum, item) => sum + item.subtotal, 0);
  const totalEl = document.getElementById("saleTotal");

  if (totalEl) {
    totalEl.textContent = `Total: $${total.toFixed(2)}`;
  }
}

const saveSaleBtn = document.getElementById("saveSale");

if (saveSaleBtn) {
  saveSaleBtn.addEventListener("click", () => {
    if (currentSaleItems.length === 0) {
      alert("No items in sale");
      return;
    }

    const date = document.getElementById("saleDate").value;

    if (!date) {
      alert("Please select a date");
      return;
    }

    const total = currentSaleItems.reduce((sum, item) => sum + item.subtotal, 0);

    fetch("actions/sale_create.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        date: date,
        total: total,
        items: currentSaleItems
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Sale saved successfully");

        currentSaleItems = [];
        renderSaleDetails();

        document.getElementById("saleDate").value = "";
        document.getElementById("saleProduct").value = "";
        document.getElementById("saleQuantity").value = "";
        document.getElementById("salePrice").value = "";
      } else {
        alert("Error saving sale");
      }
    });
  });
}