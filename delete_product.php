<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'handmade_shop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Delete the product from the database
    $deleteSql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $productId);  // Ensure 'i' is used for integer values

    if ($stmt->execute()) {
        echo "<div class='success-message'>Product deleted successfully!</div>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'admindashboard.php';
                }, 2000);
              </script>";
    } else {
        echo "<div class='error-message'>Error deleting product: " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "Product ID is missing!";
}

$conn->close();
?>
