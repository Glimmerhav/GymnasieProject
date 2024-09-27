document.getElementById('productForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const productName = document.getElementById('productName').value;
    const productPrice = document.getElementById('productPrice').value;
    const productDescription = document.getElementById('productDescription').value;

    const product = {
        name: productName,
        price: productPrice,
        description: productDescription
    };

    // Get existing products from localStorage or set an empty array if none exist
    let products = JSON.parse(localStorage.getItem('products')) || [];

    // Add the new product to the array
    products.push(product);

    // Save the updated products array back to localStorage
    localStorage.setItem('products', JSON.stringify(products));

    // Clear form
    document.getElementById('productForm').reset();

    alert('Product added successfully!');
});
