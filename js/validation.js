document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("product_form");
    const saveBtn = document.getElementById("saveBtn");
    
    // Function to fetch existing SKUs from the server
    function fetchExistingSKUs(callback) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "check-sku.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const existingSKUs = JSON.parse(xhr.responseText);
                callback(existingSKUs); // Pass the existingSKUs to the callback function
            }
        };
        xhr.send();
    }

    // Listen for mouseclicks
    saveBtn.addEventListener("click", function(event) {
        event.preventDefault(); 
        
        // Retrieve form data
        const SKU = document.getElementById("sku").value.trim();
        const name = document.getElementById("name").value.trim();
        const price = parseFloat(document.getElementById("price").value.trim());
        const selectedType = document.getElementById("productType").value;  
        
        // Fetch existing SKUs and perform validation inside the callback
        fetchExistingSKUs(function(existingSKUs) {
            let errorMessage = ""; // Variable to store error message
            
            // Check SKU uniqueness and main attributes using existingSKUs data
            if (SKU && existingSKUs.includes(SKU)) {
                errorMessage = "SKU must be unique. This SKU is already in use.";
            } else if (!SKU) {
            errorMessage = "Please enter SKU.";
            } else if (!name) {
            errorMessage = "Please enter name.";
            } else if (isNaN(price) || price <= 0) {
            errorMessage = "Please enter a valid numeric value greater than 0 for price.";
            } else if (selectedType === "blank") {
            errorMessage = "Please select product type!";
            } else {
            
            // Validate specific attributes based on product type
            if (selectedType === "DVD") {
                specificAttribute = parseFloat(document.getElementById("size").value.trim());
                if (isNaN(specificAttribute) || specificAttribute <= 0) {
                    errorMessage = "Please enter a valid numeric value greater than 0 for size.";
                }
            } else if (selectedType === "book") {
                specificAttribute = parseFloat(document.getElementById("weight").value.trim());
                if (isNaN(specificAttribute) || specificAttribute <= 0) {
                    errorMessage = "Please enter a valid numeric value greater than 0 for weight.";
                }
            } else if (selectedType === "furniture") {
                const height = parseFloat(document.getElementById("height").value.trim());
                const width = parseFloat(document.getElementById("width").value.trim());
                const length = parseFloat(document.getElementById("length").value.trim());

                if (isNaN(height) || height <= 0) {
                    errorMessage = "Please enter a valid numeric value greater than 0 for height.";
                } else if (isNaN(width) || width <= 0) {
                    errorMessage = "Please enter a valid numeric value greater than 0 for width.";
                } else if (isNaN(length) || length <= 0) {
                    errorMessage = "Please enter a valid numeric value greater than 0 for length.";
                } else {
                    specificAttribute = `${height}x${width}x${length}`;
                }
            }
        }

            // Display error message if any
            document.getElementById("error-message").textContent = errorMessage;
            document.getElementById("error-message").style.display = errorMessage ? "block" : "none";

            // Hide the error message after 3 seconds
            if (errorMessage) {
                setTimeout(function() {
                    document.getElementById("error-message").style.display = "none";
                }, 3000); // 3000 milliseconds = 3 seconds
            }
        });
    });
});