<?php
require 'db.php';

if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];

    // Prepare the SQL statement
    $stmt = $pdo->prepare('DELETE FROM pesanan WHERE id = ?');
    
    // Execute the SQL statement
    if ($stmt->execute([$order_id])) {
        // Redirect to the order list page with a success message
        header('Location: orders.php?message=Order+deleted+successfully');
        exit();
    } else {
        // Redirect to the order list page with an error message
        header('Location: orders.php?message=Failed+to+delete+order');
        exit();
    }
} else {
    // Redirect to the order list page with an error message if no ID is provided
    header('Location: orders.php?message=Invalid+order+ID');
    exit();
}
?>
