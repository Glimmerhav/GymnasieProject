window.onload = function () {
    const productList = document.getElementById('productList');

    // Fetch products from localStorage
    const products = JSON.parse(localStorage.getItem('products')) || [];

    // Display each product
    products.forEach(product => {
        const li = document.createElement('li');
        li.innerHTML = `
            <h3>${product.name}</h3>
            <p>Price: $${product.price}</p>
            <p>${product.description}</p>
        `;
        productList.appendChild(li);
    });
};
