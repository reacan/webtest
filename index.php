<?php include('db_dpo.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="css/main.css">

</head>

<body>
    <div class="viewport">
    <div class="header">
    <h1>Product List</h1>
    <button id="addBtn">ADD</button>
    <button id="delete-product-btn">MASS DELETE</button>
    </div>
    <hr>
    <div id="app" class="products-grid">

        
    <div class="product" v-for="product in products" :key="product.product_id">
    <!-- Checkbox for mass delete -->
    <input type="checkbox" v-model="selectedProducts" :value="product.product_id" class="delete-checkbox"> 
        <!-- Display common product information -->
        <p>{{ product.SKU }}</p>
        <p>{{ product.name }}</p>
        <p>{{ product.price }} $</p>
        
        <!-- Display product specific information based on product type -->
        <template v-if="product.SizeMb !== null">
            <p>Size: {{ formatDecimal(product.SizeMb) }} MB</p>
        </template>
        <template v-else-if="product.WeightKg !== null">
            <p>Weight: {{ formatDecimal(product.WeightKg) }} KG</p>
        </template>
        <template v-else-if="product.HeightCm !== null">
            <p>Dimension: {{ formatDecimal(product.HeightCm) }}x{{ formatDecimal(product.WidthCm) }}x{{ formatDecimal(product.LengthCm) }} CM</p>
        </template>
        <template v-else>
            <p>No specific information available for this product.</p>
        </template>

    </div>
</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="js/app.js" defer></script>

<div class="footer">
<hr>
Scandiweb Test assignment
</div>

</body>




</html>