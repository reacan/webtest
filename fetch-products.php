<?php
include('db_dpo.php');

$sql = "SELECT products.product_id, products.SKU, products.name, products.price, 
        DVD.SizeMb, book.WeightKg, furniture.HeightCm, furniture.WidthCm, furniture.LengthCm
        FROM products
        LEFT JOIN DVD ON products.product_id = DVD.product_id
        LEFT JOIN book ON products.product_id = book.product_id
        LEFT JOIN furniture ON products.product_id = furniture.product_id";

$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?>