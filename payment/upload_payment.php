<?php
include 'includes/session.php';

if(isset($_POST['submit'])){
    // Directory where the file will be uploaded
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["payment_proof"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an actual image or fake image
    $check = getimagesize($_FILES["payment_proof"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['error'] = "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $_SESSION['error'] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["payment_proof"]["size"] > 500000) { // 500KB limit
        $_SESSION['error'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        header('location: cart_view.php');
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
            // Save to database
            $conn = $pdo->open();
            $payment_proof = $target_file; // assuming payment_proof is the file path
            $user_id = $_SESSION['user']['id'];
            $sales_date = date('Y-m-d');

            try {
                $stmt = $conn->prepare("INSERT INTO sales (user_id, payment_proof, sales_date) VALUES (:user_id, :payment_proof, :sales_date)");
                $stmt->execute(['user_id'=>$user_id, 'payment_proof'=>$payment_proof, 'sales_date'=>$sales_date]);

                $_SESSION['success'] = "Payment proof uploaded successfully.";
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage();
            }

            $pdo->close();
            header('location: cart_view.php');
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
            header('location: cart_view.php');
        }
    }
} else {
    $_SESSION['error'] = "Please upload a file.";
    header('location: cart_view.php');
}
?>
