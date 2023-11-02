<?php
require_once('db_dpo.php');

// Fetch existing SKUs from the database
$sql = "SELECT SKU FROM products";
$result = $conn->query($sql);
$existingSKUs = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $existingSKUs[] = $row["SKU"];
    }
}

echo json_encode($existingSKUs);

$conn->close();
?>