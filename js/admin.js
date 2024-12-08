document.getElementById('productForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const productName = document.getElementById('productName').value;
    const productPrice = document.getElementById('productPrice').value;
    const productDescription = document.getElementById('productDescription').value;
    const productStock = document.getElementById('productStock').value;
    const productImageInput = document.getElementById('productImage');

    if (productImageInput.files.length === 0) {
        alert('Please select an image.');
        return;
    }

    // Skicka produkten till servern
    const formData = new FormData();
    formData.append('productName', productName);
    formData.append('productPrice', productPrice);
    formData.append('productDescription', productDescription);
    formData.append('productStock', productStock);
    formData.append('productImage', productImageInput.files[0]);

    fetch('/php/upload.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text())  // För att få ett text-svar från PHP
    .then(text => {
        if (text.includes('Product added successfully!')) {
            document.getElementById('productForm').reset();
            alert('Product added successfully!');
            refreshProductListAdmin();
            closeAddProductModal();
            createProductPageOnServer(productName, productPrice, productDescription, productStock, '/img/uploads/' + productImageInput.files[0].name);
            if (window.opener && window.opener.refreshProductList) {
                window.opener.refreshProductList();
            }
        } else {
            alert('Failed to add product: ' + text);
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the product.');
    });
});

function refreshProductListAdmin() {
    const productListAdmin = document.getElementById('productListAdmin');
    productListAdmin.innerHTML = ''; // Clear existing list

    // Hämta produkter från servern
    fetch('/data/products.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(products => {
            // Display each product with a delete button
            products.forEach((product, index) => {
                const productDiv = document.createElement('div');
                productDiv.classList.add('product-item');
                productDiv.innerHTML = `
                    <a href="/html/products/${product.name.replace(/\s+/g, '_')}.html">
                        <img src="${product.image}" alt="Product Image">
                        <h3 class="product-title">${product.name}</h3>
                        <p class="product-description">${product.description}</p>
                        <p class="product-price">Price: $${product.price}</p>
                    </a>
                    <button class="delete-button" onclick="deleteProduct(${index})">Delete Product</button>
                `;
                productListAdmin.appendChild(productDiv);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching products');
        });
}

function deleteProduct(index) {
    // Hämta produkter från servern
    fetch('/data/products.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(products => {
            // Ta bort produkten från listan
            products.splice(index, 1);

            // Skriv tillbaka till JSON-filen
            fetch('/php/delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(products)
            }).then(response => response.text())
            .then(text => {
                if (text.includes('Product deleted successfully!')) {
                    alert('Product deleted successfully!');
                    refreshProductListAdmin();
                    if (window.opener && window.opener.refreshProductList) {
                        window.opener.refreshProductList();
                    }
                } else {
                    alert('Failed to delete product: ' + text);
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the product.');
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching products');
        });
}

function createProductPageOnServer(name, price, description, stock, imageUrl) {
    // Skicka produktdata till PHP för att skapa HTML-sidan
    const productData = {
        name: name,
        price: price,
        description: description,
        stock: stock,
        image: imageUrl
    };

    fetch('/php/create_product_page.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(productData)
    }).then(response => response.text())
    .then(text => {
        if (text.includes('Product page created successfully!')) {
            console.log('Product page created successfully!');
        } else {
            console.error('Failed to create product page: ' + text);
        }
    }).catch(error => {
        console.error('Error:', error);
    });
}

window.onload = function () {
    refreshProductListAdmin();
};

// Modal functions
const addProductButton = document.getElementById('addProductButton');
const addProductModal = document.getElementById('addProductModal');

addProductButton.addEventListener('click', () => {
    addProductModal.style.display = 'block';
});

function closeAddProductModal() {
    addProductModal.style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == addProductModal) {
        addProductModal.style.display = 'none';
    }
}
