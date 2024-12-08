window.onload = function () {
    refreshProductList();
};

function refreshProductList() {
    const productList = document.getElementById('productList');
    productList.innerHTML = ''; // Clear existing list

    // Hämta produkter från servern
    fetch('/data/products.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(products => {
            // Display each product
            products.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.classList.add('product-item');
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="Product Image">
                    <h3 class="product-title">${product.name}</h3>
                    <p class="product-description">${product.description}</p>
                    <p class="product-price">Price: $${product.price}</p>
                    <button class="order-button" onclick="orderProduct('${product.name}')">Order Now</button>
                `;
                productList.appendChild(productDiv);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching products');
        });
}

function orderProduct(productName) {
    alert(`You have ordered: ${productName}`);
    // Här kan du lägga till funktionalitet för att lägga produkten i en kundvagn
}

window.refreshProductList = refreshProductList; // Gör funktionen tillgänglig för admin-sidan