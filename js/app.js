new Vue({
    el: '#app',
    data: {
        products: [], // Array to store products from the database
        selectedProducts: [] // Array to store selected products for deletion
    },
    mounted() {
        // Fetch products from the database when the Vue instance is mounted
        this.fetchProductsFromDB();
        // Add event listener to the delete button
        const deleteButton = document.getElementById("delete-product-btn");
        deleteButton.addEventListener("click", this.deleteSelectedProducts);
        //Event listener for the add button
        const addButton = document.getElementById("addBtn");
        addButton.addEventListener("click", this.navigateToAddProduct);
        
    },
    methods: {
        navigateToAddProduct() {
            // Redirect to add-product.html when the Add button is clicked
            console.log("Add button clicked!")
            window.location.href = 'add-product.html';
        },
        formatDecimal(value) {
            // Convert string to numeric value if it's a string
            const numericValue = typeof value === 'string' ? parseFloat(value) : value;

            if (Number.isInteger(numericValue)) {
            return numericValue.toString(); // If the value is an integer, return it as a string
            } else {
            return numericValue.toFixed(2).replace(/\.?0*$/, ''); // Format decimal values and remove trailing zeros
            }
        },
        fetchProductsFromDB() {
            fetch('fetch-products.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.products = data; // Update products array with fetched data
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    // Handle the error gracefully, e.g., show an error message to the user
                });
        },
        deleteSelectedProducts() {
            console.log("Delete button clicked!"); // Check if the function is triggered
            console.log("Selected Products: ", this.selectedProducts); // Check the selected products array
            // Filter out selected products
            const productsToDelete = this.products.filter(product => this.selectedProducts.includes(product.product_id));
            
            // API request to delete products
            fetch('delete-products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ productsToDelete })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted products from the products array
                    this.products = this.products.filter(product => !this.selectedProducts.includes(product.product_id));
                    // Clear selectedProducts array
                    this.selectedProducts = [];
        console.log("Products deleted successfully."); // Log to console if you want

                } else {
        console.error("Failed to delete products."); // Log to console if you want

                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
});