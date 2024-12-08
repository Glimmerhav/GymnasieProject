<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = '../img/uploads/';
    $htmlDir = '../html/products/';
    $jsonPath = '../data/products.json';

    // Kontrollera om uppladdningsmappen och HTML-mappen finns, skapa om inte
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if (!is_dir($htmlDir)) {
        mkdir($htmlDir, 0777, true);
    }

    // Ladda upp bildfilen
    $uploadFile = $uploadDir . basename($_FILES['productImage']['name']);
    if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadFile)) {
        http_response_code(500);
        echo 'Failed to upload image.';
        exit;
    }

    // Spara produktinformation i JSON-filen
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productDescription = $_POST['productDescription'];
    $productStock = $_POST['productStock'];
    $productImage = $uploadFile;

    $product = array(
        'name' => $productName,
        'price' => $productPrice,
        'description' => $productDescription,
        'stock' => $productStock,
        'image' => $productImage
    );

    // Kontrollera om JSON-filen finns, annars skapa den
    if (!file_exists($jsonPath)) {
        file_put_contents($jsonPath, json_encode([]));
    }

    // Läs in de befintliga produkterna
    $products = json_decode(file_get_contents($jsonPath), true);
    if ($products === null) {
        http_response_code(500);
        echo 'Failed to read product information from JSON file.';
        exit;
    }

    // Lägg till den nya produkten
    $products[] = $product;

    // Skriv tillbaka till JSON-filen
    if (!file_put_contents($jsonPath, json_encode($products, JSON_PRETTY_PRINT))) {
        http_response_code(500);
        echo 'Failed to save product information to JSON file.';
        exit;
    }

    // Generera HTML-innehåll för produktsidan
    $productPageContent = "
    <!DOCTYPE html>
    <html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>$productName</title>
        <link rel=\"stylesheet\" href=\"/css/styles-for-index.css\">
        <link href=\"https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap\" rel=\"stylesheet\">
    </head>
    <body>
        <header>
            <h1>$productName</h1>
        </header>
        <section class=\"product-details\">
            <img src=\"$productImage\" alt=\"Product Image\">
            <h3 class=\"product-title\">$productName</h3>
            <p class=\"product-description\">$productDescription</p>
            <p class=\"product-price\">Price: $$productPrice</p>
            <p class=\"product-stock\">Stock: $productStock</p>
        </section>
    </body>
    </html>
    ";

    // Skriv HTML-innehållet till en fil
    $fileName = str_replace(' ', '_', $productName) . '.html';
    $filePath = $htmlDir . $fileName;
    if (!file_put_contents($filePath, $productPageContent)) {
        http_response_code(500);
        echo 'Failed to create product page.';
        exit;
    }

    http_response_code(200);
    echo 'Product added successfully!';
} else {
    http_response_code(400);
    echo 'Invalid request method.';
}
?>
