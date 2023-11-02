document.addEventListener("DOMContentLoaded", function() {
    const productTypeSelect = document.getElementById("productType");
    const typeContainers = {
        DVD: document.getElementById("DVD"),
        book: document.getElementById("book"),
        furniture: document.getElementById("furniture")
    };

    productTypeSelect.addEventListener("change", function() {
        for (const type in typeContainers) {
            if (typeContainers.hasOwnProperty(type)) {
                typeContainers[type].classList.add("hidden");
            }
        }
        const selectedType = productTypeSelect.value;
        if (typeContainers.hasOwnProperty(selectedType)) {
            typeContainers[selectedType].classList.remove("hidden");
        }
    });

    const product_form = document.getElementById("product_form");
    const saveBtn = document.getElementById("saveBtn");
    const cancelBtn = document.getElementById("cancelBtn");

    saveBtn.addEventListener("click", function(event) {
        event.preventDefault();
        // Get form field values and populate the data object
        const data = {
            SKU: document.getElementById("sku").value,
            name: document.getElementById("name").value,
            price: document.getElementById("price").value,
            productType: document.getElementById("productType").value,
            SizeMb: document.getElementById("size").value,
            WeightKg: document.getElementById("weight").value,
            HeightCm: document.getElementById("height").value,
            WidthCm: document.getElementById("width").value,
            LengthCm: document.getElementById("length").value
        };

        // Send form data to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "save-product.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the server response here if needed


                console.log(xhr.responseText);
            }
        };

        // Convert data object to URL-encoded format
        const urlEncodedData = Object.keys(data).map(key => encodeURIComponent(key) + "=" + encodeURIComponent(data[key])).join("&");

        xhr.send(urlEncodedData);
    });


    cancelBtn.addEventListener("click", function(event) {
        event.preventDefault();
        window.location.href = "index.php";
    });
    

});