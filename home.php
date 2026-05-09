<?php
session_start();
include './db.php'; // Include the database connection

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: pages/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="./img/brand name.png" alt="Brand Logo" class="logo">
            <nav>
                <a href="pages/login.php">Login</a>
                <a href="pages/register.php">Register</a>
                <a href="pages/cart.php" class="cart-link">
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/070/342/347/small/shiny-chrome-shopping-cart-icon-representing-online-retail-and-e-commerce-isolated-on-transparent-background-png.png" alt="Cart" class="cart-icon">
                    Cart
                </a>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="logout-button">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    <div class="main-container">
        <main>
            <h2>Products</h2>
            <div class="product-list">
                <div class="product">
                     <img src="img/iqoo 15 5g.webp" alt="iQOO 15 5G" class="product-image">
                    <h3>iQOO 15 5G</h3>
                    <p>Price: ₹41,417</p>
                    <p>Latest smartphone with 5G connectivity, high-performance processor, and stunning display.</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="1">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
                <div class="product">
                    <img src="img/lg washing machine.webp" alt="LG Washing Machine" class="product-image">
                    <h3>LG Washing Machine</h3>
                    <p>Price: ₹66,317</p>
                    <p>Efficient and reliable washing machine with multiple wash cycles and energy-saving features.</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="2">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
                <div class="product">
                    <img src="img/realme p4x 5g.jpg" alt="Realme P4X 5G" class="product-image">
                    <h3>Realme P4X 5G</h3>
                    <p>Price: ₹24,817</p>
                    <p>Affordable 5G smartphone with great camera, long battery life, and sleek design.</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="3">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
                <div class="product">
                    <img src="img/alexa.webp" alt="Amazon Alexa" class="product-image">
                    <h3>Amazon Alexa</h3>
                    <p>Price: ₹8,217</p>
                    <p>Smart home assistant with voice control, music playback, and smart device integration.</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="4">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
                <div class="product">
                    <img src="img/redmi note 15.webp" alt="Redmi Note 15" class="product-image">
                    <h3>Redmi Note 15</h3>
                    <p>Price: ₹28,967</p>
                    <p>Powerful budget smartphone with excellent camera, fast charging, and long-lasting battery.</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="5">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
                <div class="product">
                    <img src="img/71JGCn1z1TL._SX679_.jpg" alt="Special Item" class="product-image">
                    <h3>Special Item</h3>
                    <p>Price: ₹16,517</p>
                    <p>Unique product with premium quality and special features.</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="6">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
                <div class="product">
                    <img src="img/asus laptop.jpg" alt="Special Item" class="product-image">
                    <h3>Asus Laptop</h3>
                    <p>Price: ₹50,999</p>
                    <p>ASUS TUF F16,14th Gen,Intel Core i7 14650HX,Gaming Laptop(RTX 5070-8GB/115W TGP/32GB/1TB /2.5K QHD+/16"/165Hz)</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="6">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
                <div class="product">
                    <img src="img/sound bar.webp" alt="Special Item" class="product-image">
                    <h3>Sound Bar</h3>
                    <p>Price: ₹15,999</p>
                    <p>Enhance your home entertainment with this powerful sound bar.</p>
                    <form method="POST" action="pages/cart.php">
                        <input type="hidden" name="product_id" value="7">
                        <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>