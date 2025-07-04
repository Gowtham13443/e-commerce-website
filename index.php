<?php
session_start();
include 'includes/db.php'; // Include the database connection

// Fetch products from the database
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Online Store</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />

    <style>
    /* Dark Mode Modern & Aesthetic CSS */

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: #121212;
        color: #e0e0e0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    header {
        background: #1f1f1f;
        padding: 25px 40px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.8);
        position: sticky;
        top: 0;
        z-index: 100;
        border-radius: 0 0 30px 30px;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    header h1 {
        font-weight: 700;
        font-size: 2.8rem;
        color: #ffffff;
        letter-spacing: 1.2px;
    }

    nav {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    nav a, .logout-button {
        color: #bbb;
        font-weight: 500;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1.1px;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 50px;
        background: #222222;
        box-shadow:
          6px 6px 10px #121212,
          -6px -6px 10px #2a2a2a;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    nav a:hover, .logout-button:hover {
        background: #4a90e2;
        color: #fff;
        box-shadow:
          inset 6px 6px 10px #357abd,
          inset -6px -6px 10px #5ea0ff;
    }

    .logout-button {
        border: none;
        background: #e94e77;
        color: #fff;
        box-shadow:
          6px 6px 10px #9b2c4a,
          -6px -6px 10px #ff6a8d;
        font-weight: 600;
    }

    .logout-button:hover {
        background: #d1335b;
        box-shadow:
          inset 6px 6px 10px #7e2740,
          inset -6px -6px 10px #ff5178;
    }

    .cart-icon {
        width: 26px;
        height: 26px;
        filter: drop-shadow(1px 1px 1px rgba(0,0,0,0.8));
    }

    .main-container {
        flex-grow: 1;
        padding: 50px 20px;
        background: #121212;
        display: flex;
        justify-content: center;
    }

    .product-list {
        max-width: 1200px;
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 32px;
    }

    .product {
        background: #1f1f1f;
        border-radius: 25px;
        padding: 30px 25px 40px 25px;
        box-shadow:
          15px 15px 30px #0a0a0a,
          -15px -15px 30px #2f2f2f;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .product:hover {
        transform: translateY(-12px);
        box-shadow:
          25px 25px 50px #050505,
          -25px -25px 50px #404040;
    }

    .product h3 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 15px;
        color: #e0e0e0;
        text-align: center;
    }

    .product p {
        flex-grow: 1;
        font-weight: 300;
        font-size: 1rem;
        color: #aaaaaa;
        margin-bottom: 25px;
        line-height: 1.5;
        text-align: center;
    }

    .product-image {
        width: 100%;
        height: 180px;
        border-radius: 20px;
        object-fit: cover;
        margin-bottom: 25px;
        box-shadow:
          8px 8px 15px #0e0e0e,
          -8px -8px 15px #383838;
        transition: box-shadow 0.3s ease;
    }

    .product:hover .product-image {
        box-shadow:
          12px 12px 25px #040404,
          -12px -12px 25px #4a4a4a;
    }

    .add-to-cart-button {
        font-weight: 600;
        font-size: 1.15rem;
        padding: 14px 0;
        border-radius: 50px;
        border: none;
        background: #005f99;
        background-image: linear-gradient(145deg, #007acc, #003d66);
        color: #cce6ff;
        box-shadow:
          7px 7px 15px #00334d,
          -7px -7px 15px #3399ff;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
        user-select: none;
        width: 100%;
    }

    .add-to-cart-button:hover {
        background-image: linear-gradient(145deg, #3399ff, #0066cc);
        transform: scale(1.08);
        box-shadow:
          inset 5px 5px 10px #005280,
          inset -5px -5px 10px #99ccff;
    }

    footer {
        background: #1f1f1f;
        padding: 25px 15px;
        box-shadow: 0 -6px 15px rgba(0,0,0,0.8);
        text-align: center;
        font-weight: 500;
        font-size: 1.05rem;
        color: #999999;
        border-radius: 30px 30px 0 0;
        margin-top: auto;
    }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Welcome to Our Store</h1>
            <nav>
                <a href="pages/login.php">Login</a>
                <a href="pages/register.php">Register</a>
                <a href="pages/cart.php" class="cart-link">
                    <img src="images/cart-icon.png" alt="Cart" class="cart-icon" />
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
                <?php if (empty($products)) : ?>
                    <p>No products available.</p>
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="product">
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <p>Price: $<?= number_format($product['price'], 2); ?></p>
                            <p><?= htmlspecialchars($product['description']); ?></p>
                            <?php if (!empty($product['image'])) : ?>
                                <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image" />
                            <?php endif; ?>
                            <form method="POST" action="pages/cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>" />
                                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
