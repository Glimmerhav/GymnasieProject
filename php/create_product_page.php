<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Få produktdata från anropet
    $input = file_get_contents('php://input');
    $productData = json_decode($input, true);

    if (!$productData) {
        http_response_code(400);
        echo 'Invalid product data.';
        exit;
    }

    // Produktinformation
    $name = htmlspecialchars($productData['name']);
    $price = htmlspecialchars($productData['price']);
    $description = htmlspecialchars($productData['description']);
    $stock = htmlspecialchars($productData['stock']);
    $imageUrl = htmlspecialchars($productData['image']);
    $availableSizes = ['128', '134/140', '146/152', '158/164', '170']; // Exempelstorlekar

    // Generera HTML-innehåll med mer strukturerad layout och storleksalternativ
    $productPageContent = "
    <!DOCTYPE html>
    <html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>$name</title>
        <link rel=\"stylesheet\" href=\"/css/product-styles.css\">
        <link href=\"https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap\" rel=\"stylesheet\">
        <link rel=\"stylesheet\" href=\"/css/product-page.css\">
    </head>
    <body>
        <header>
            <h1>$name</h1>
        </header>
        <section class=\"product-details\">
            <div class=\"product-gallery\">
                <img src=\"$imageUrl\" alt=\"$name Image\" class=\"product-image-main\">
                <div class=\"product-thumbnails\">
                    <img src=\"$imageUrl\" alt=\"$name Thumbnail 1\" class=\"product-thumbnail\">
                    <img src=\"$imageUrl\" alt=\"$name Thumbnail 2\" class=\"product-thumbnail\">
                    <img src=\"$imageUrl\" alt=\"$name Thumbnail 3\" class=\"product-thumbnail\">
                </div>
            </div>
            <div class=\"product-info\">
                <h2 class=\"product-title\">$name</h2>
                <p class=\"product-price\">Price: $$price</p>
                <p class=\"product-description\">$description</p>
                <p class=\"product-stock\">Stock: $stock</p>
                
                <label for=\"productSize\">Välj storlek:</label>
                <div class=\"product-sizes\">
    ";

    foreach ($availableSizes as $size) {
        $productPageContent .= "<button class=\"size-button\">$size</button>";
    }

    $productPageContent .= "
                </div>
                <button class=\"order-button\">LÄGG TILL I SHOPPINGBAGEN</button>
            </div>
        </section>
    </body>
    </html>
    ";

    // Filnamn och sökväg
    $fileName = str_replace(' ', '_', $name) . '.html';
    $filePath = '/html/products/' . $fileName;

    // Skriv HTML-innehållet till en fil
    if (file_put_contents($filePath, $productPageContent) !== false) {
        http_response_code(200);
        echo 'Product page created successfully!';
    } else {
        http_response_code(500);
        echo 'Failed to create product page.';
    }
} else {
    http_response_code(400);
    echo 'Invalid request method.';
}
?>
