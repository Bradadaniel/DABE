<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
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

    <style>
        body{
            background: url("../img/dashboard_bg.jpg");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>

<?php include 'admin_header.php'?>

<section class="dashboard">

    <h1 class="heading">Admin panel</h1>

<div class="box-container">

    <div class="box">
        <h3>Üdvözöllek!</h3>
        <p><?=$fetch_profile['name'];?></p>
        <a href="update_profile.php" class="btn">Profil szerkesztése</a>
    </div>

    <div class="box">
        <?php
        $total_pendings = 0;
        $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
        $select_pendings->execute(['pending']);
        while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_price'];
        }
        ?>
        <h3><span></span><?= $total_pendings; ?><span> RSD</span></h3>
        <p>Teljes függőben levő</p>
        <a href="placed_orders.php" class="btn">Megtekintés</a>
    </div>


    <div class="box">
        <?php
        $total_completes = 0;
        $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
        $select_completes->execute(['completed']);
        while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes += $fetch_completes['total_price'];
        }
        ?>
        <h3><span></span><?= $total_completes; ?><span> RSD</span></h3>
        <p>Teljes befejezett rendelés</p>
        <a href="placed_orders.php" class="btn">Megtekintés</a>
    </div>

    <div class="box">
        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders`");
        $select_orders->execute();
        $numbers_of_orders = $select_orders->rowCount();
        ?>
        <h3><?= $numbers_of_orders?></h3>
        <p>Teljes rendelés</p>
        <a href="placed_orders.php" class="btn">Megtekintés</a>
    </div>


    <div class="box">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products`");
        $select_products->execute();
        $numbers_of_products = $select_products->rowCount();
        ?>
        <h3><?= $numbers_of_products?></h3>
        <p>Összes termék</p>
        <a href="products.php" class="btn">Termékek</a>
    </div>


    <div class="box">
        <?php
        $select_users = $conn->prepare("SELECT * FROM `users`");
        $select_users->execute();
        $numbers_of_users = $select_users->rowCount();
        ?>
        <h3><?= $numbers_of_users?></h3>
        <p>Összes felhasználó</p>
        <a href="users_account.php" class="btn">Felhasználók</a>
    </div>


    <div class="box">
        <?php
        $select_admins = $conn->prepare("SELECT * FROM `admins`");
        $select_admins->execute();
        $numbers_of_admins = $select_admins->rowCount();
        ?>
        <h3><?= $numbers_of_admins?></h3>
        <p>Összes admin</p>
        <a href="admins_account.php" class="btn">Adminok</a>
    </div>


    <div class="box">
        <?php
        $select_messages = $conn->prepare("SELECT * FROM `messages`");
        $select_messages->execute();
        $numbers_of_messages = $select_messages->rowCount();
        ?>
        <h3><?= $numbers_of_messages?></h3>
        <p>Összes üzenet</p>
        <a href="messages.php" class="btn">Üzenetek</a>
    </div>

</div>

</section>






<script src="../js/admin_script.js"></script>

</body>
</html>