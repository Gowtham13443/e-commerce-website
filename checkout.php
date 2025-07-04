<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Check if form submitted by POST (Confirm Order)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Here you could save order details to DB if you want

    // Clear the user's cart as order is confirmed
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $order_completed = true;
} else {
    $order_completed = false;

    // Fetch cart items to show
    $stmt = $conn->prepare("SELECT products.name, products.price, cart.quantity 
                            FROM cart 
                            JOIN products ON cart.product_id = products.id 
                            WHERE cart.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0; padding: 0;
        }
        .checkout-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
        }
        h2 { margin-bottom: 20px; }
        table {
            width: 100%; border-collapse: collapse; margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ccc; padding: 10px; text-align: center;
        }
        .form-group { margin-bottom: 15px; text-align: left; }
        input[type="text"], textarea {
            width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box;
        }
        .confirm-btn, .back-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .confirm-btn:hover, .back-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
<div class="checkout-container">

<?php if ($order_completed): ?>
    <h2>Thank you for your order!</h2>
    <p>Your order has been received and is being processed.</p>
    <a href="../index.php" class="back-btn">Back to Shopping</a>

<?php else: ?>

    <h2>Checkout</h2>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. <a href="../index.php">Go back to shopping</a></p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product</th><th>Price</th><th>Quantity</th><th>Total</th>
            </tr>
            <?php $grand_total = 0; ?>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td>$<?= number_format($item['price'], 2); ?></td>
                    <td><?= $item['quantity']; ?></td>
                    <td>$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php $grand_total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td><strong>$<?= number_format($grand_total, 2); ?></strong></td>
            </tr>
        </table>

        <form method="POST" action="">
            <div class="form-group">
                <label for="address">Shipping Address:</label><br>
                <textarea name="address" id="address" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label><br>
                <input type="text" name="phone" id="phone" required>
            </div>

            <button type="submit" class="confirm-btn">Confirm Order</button>
        </form>
    <?php endif; ?>

<?php endif; ?>

</div>
</body>
</html>
