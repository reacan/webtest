<?php
include('db_dpo.php');
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get data from the request payload
$data = json_decode(file_get_contents("php://input"), true);
// Initialize the response array
if (isset($data['productsToDelete']) && is_array($data['productsToDelete'])) {
    $productsToDelete = $data['productsToDelete'];
    $response = [
        'success' => false,
        'message' => 'Failed to delete products.'
    ];
    try {
        // Start a transaction
        $pdo->beginTransaction();
        // Loop through products to delete
        foreach ($productsToDelete as $product) {
            $productId = $product['product_id'];
            $stmt1 = $pdo->prepare("DELETE FROM furniture WHERE product_id = ?");
            $stmt1->execute([$productId]);
            $stmt2 = $pdo->prepare("DELETE FROM DVD WHERE product_id = ?");
            $stmt2->execute([$productId]);
            $stmt3 = $pdo->prepare("DELETE FROM book WHERE product_id = ?");
            $stmt3->execute([$productId]);
            $stmt4 = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt4->execute([$productId]);
        }
        // Commit the transaction
        $pdo->commit();
        // Set success response
        $response['success'] = true;
    } catch (Exception $e) {
        // If an error occurred, rollback the transaction
        $pdo->rollBack();
        // Log the error for debugging
        error_log($e->getMessage());
        // Set error response
        $response['message'] = 'Failed to delete products. Error: ' . $e->getMessage();
    }
    // Send the response back to the frontend
    echo json_encode($response);
// Handle invalid or missing data in the request
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid JSON format or missing productsToDelete key.'
    ];
    echo json_encode($response);
}
?>