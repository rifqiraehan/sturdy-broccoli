<?php
include 'includes/session.php';

if(isset($_POST['checkout'])){
    $date = date('Y-m-d');

    // Handle file upload
    if(isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0){
        $target_dir = "uploads/"; // Change this to your desired upload directory
        $target_file = $target_dir . basename($_FILES["payment_proof"]["name"]);

        // Move uploaded file to target directory
        if(move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)){
            $payid = $target_file;
        } else {
            $_SESSION['error'] = 'Failed to upload payment proof.';
            header('location: cart_view.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'No payment proof uploaded or upload error.';
        header('location: cart_view.php');
        exit();
    }

    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("INSERT INTO sales (user_id, payment_proof, sales_date) VALUES (:user_id, :payment_proof, :sales_date)");
        $stmt->execute(['user_id'=>$user['id'], 'payment_proof'=>$payid, 'sales_date'=>$date]);
        $salesid = $conn->lastInsertId();

        try{
            $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
            $stmt->execute(['user_id'=>$user['id']]);

            foreach($stmt as $row){
                $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
                $stmt->execute(['sales_id'=>$salesid, 'product_id'=>$row['product_id'], 'quantity'=>$row['quantity']]);
            }

            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
            $stmt->execute(['user_id'=>$user['id']]);

            $_SESSION['success'] = 'Transaction successful. Thank you.';

        } catch(PDOException $e){
            $_SESSION['error'] = $e->getMessage();
        }

    } catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}

header('location: profile.php');
?>