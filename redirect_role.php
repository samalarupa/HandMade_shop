<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];

    switch ($user_type) {
        case 'buyer':
            header("Location: collection.php"); // or wherever you want to take buyers
            break;
        case 'seller':
            header("Location: addproduct.php"); // send artisans to add products
            break;
        case 'admin':
            header("Location: adminlogin.php"); // admin panel
            break;
        default:
            header("Location: login.php"); // fallback
    }
    exit();
} else {
    header("Location: login.php");
    exit();
}
