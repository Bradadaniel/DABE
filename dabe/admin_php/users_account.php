<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
}

if (isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->execute([$delete_id]);
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_order->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
    $delete_wishlist->execute([$delete_id]);
    $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
    $delete_messages->execute([$delete_id]);

    header('location:users_account.php');
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Irányítópult</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include 'admin_header.php'?>

<section class="accounts">

    <h1 class="heading">Felhasználók</h1>

    <div class="box-container">

        <?php
        $select_account = $conn->prepare("SELECT * FROM `users`");
        $select_account->execute();
        if ($select_account->rowCount() > 0){
            while ($fetch_accounts= $select_account->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div class="box">
                    <p> Felhasználó id : <span><?= $fetch_accounts['id'];?></span> </p>
                    <p> Felhasználó neve : <span><?= $fetch_accounts['name'];?></span> </p>
                    <a href="users_account.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Törli a fiókot?');">Törlés</a>

                </div>

                <?php
            }
        }else{
            echo '<p class="empty">Nincsenek elérhető fiókok!</p>';
        }
        ?>

    </div>

</section>





<script src="../js/admin_script.js"></script>

</body>
</html>