<?php
session_start();
// Removed login check to allow access to cart page

include '../db.php';

$user_id = $_SESSION['user_id'] ?? null;  // Use null if not set

// Handle Add to Cart with Quantity
if (isset($_POST['add_to_cart']) && $user_id) {
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;  // Default to 1 if quantity is not set

    // Check if product is already in the user's cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_item = $result->fetch_assoc();

    if ($cart_item) {
        // Update quantity if the product is already in the cart
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Add new product to the cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }
}

// Handle Product Removal from Cart
if (isset($_POST['remove_from_cart']) && $user_id) {
    $product_id = (int)$_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

// Handle Quantity Update
if (isset($_POST['update_quantity']) && $user_id) {
    $product_id = (int)$_POST['product_id'];
    $quantity = max(1, (int)$_POST['quantity']);

    // Update the quantity in the cart
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $stmt->execute();
}

// Fetch the user's cart items
if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $cart_items = [];
}

$total_cost = 0;  // Initialize total cost variable
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .cart-page {
            padding: 24px 0 48px;
        }
        .cart-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: grid;
            grid-template-columns: 120px 1fr minmax(220px, auto);
            gap: 24px;
            align-items: center;
        }
        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 18px;
        }
        .item-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .item-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
        }
        .item-price {
            font-size: 1rem;
            color: var(--text-muted);
        }
        .item-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }
        .quantity {
            width: 80px;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface-alt);
            color: var(--text);
        }
        .action-button {
            min-width: 140px;
            padding: 12px 18px;
            border: none;
            border-radius: 16px;
            color: #fff;
            background: linear-gradient(135deg, #4338ca, #2563eb);
            cursor: pointer;
            transition: transform 0.2s ease, filter 0.2s ease;
        }
        .action-button:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
        }
        .remove-button {
            background: #ef4444;
        }
        .remove-button:hover {
            filter: brightness(1.1);
        }
        .cart-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 16px;
            margin-top: 32px;
        }
        .cart-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 22px;
            border-radius: 999px;
            background: linear-gradient(135deg, #4338ca, #2563eb);
            color: #fff;
            font-weight: 700;
        }
        .empty-cart {
            text-align: center;
            color: var(--text-muted);
            font-size: 1.15rem;
            padding: 40px 0;
        }
        .cart-icon{
            width: 55px;
            height: 55px;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <a href="../index.php"><img src="../img/brand name.png" alt="Brand Logo" class="logo"></a>
            <nav>
                <a href="../home.php">Home</a>
                
                    <img src="https://static.vecteezy.com/system/resources/thumbnails/070/342/347/small/shiny-chrome-shopping-cart-icon-representing-online-retail-and-e-commerce-isolated-on-transparent-background-png.png" alt="Cart" class="cart-icon">
                    
            
            </nav>
        </div>
    </header>
    <div class="main-container cart-page">
        <main>
            <h2>Your Cart</h2>
        <?php
        if (empty($cart_items)) {
            echo "<p class='empty-cart'>Your cart is empty.</p>";
        } else {
            // Fetch product details for each cart item
            $product_ids = array_map('intval', array_column($cart_items, 'product_id'));
            $id_list = implode(',', $product_ids);
            $query = "SELECT * FROM products WHERE id IN ($id_list)";
            $result = $conn->query($query);
            $products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

            foreach ($products as $product) {
                $quantity = 0;
                foreach ($cart_items as $cart_item) {
                    if ($cart_item['product_id'] == $product['id']) {
                        $quantity = $cart_item['quantity'];
                        break;
                    }
                }
                $total_cost += $product['price'] * $quantity; // Add product price * quantity to total cost

                echo "<div class='cart-item'>
                        <img src='../img/{$product['image']}' alt='{$product['name']}' class='item-image'>
                        <div class='item-details'>
                            <div class='item-name'>{$product['name']}</div>
                            <div class='item-price'>₹{$product['price']} x $quantity</div>
                        </div>
                        <div class='item-actions'>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$product['id']}'>
                                <input type='number' name='quantity' value='$quantity' class='quantity' min='1' required>
                                <button type='submit' name='update_quantity' class='action-button'>Update Quantity</button>
                            </form>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$product['id']}'>
                                <button type='submit' name='remove_from_cart' class='action-button remove-button'>Remove</button>
                            </form>
                        </div>
                      </div>";
            }
        }
        ?>
        <?php if (!empty($cart_items)) : ?>
            <div class="total-cost">
                Total: ₹<?= number_format($total_cost, 2); ?>
            </div>
        <?php endif; ?>
        <div class="cart-actions">
            <a href="../home.php">Back to Shop</a>
            <a href="checkout.php">Proceed to Checkout</a>
        </div>
        </main>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>