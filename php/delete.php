<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $products = json_decode($input, true);

    if ($products === null) {
        http_response_code(400);
        echo 'Invalid product data.';
        exit;
    }

    $jsonPath = '../data/products.json';

    // Skriv tillbaka till JSON-filen
    if (file_put_contents($jsonPath, json_encode($products, JSON_PRETTY_PRINT)) !== false) {
        http_response_code(200);
        echo 'Product deleted successfully!';
    } else {
        http_response_code(500);
        echo 'Failed to save product information to JSON file.';
    }
} else {
    http_response_code(400);
    echo 'Invalid request method.';
}
?>
