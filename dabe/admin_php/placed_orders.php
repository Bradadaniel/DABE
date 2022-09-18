<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
}

if (isset($_POST['update_payment'])){

    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $update_status = $conn->prepare("UPDATE `orders` SET payment_status =? WHERE id = ?");
    $update_status->execute([$payment_status, $order_id]);
    $message[]= 'A fizetés állapot frissítve';
}
if (isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
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

<section class="placed-orders">

    <h1 class="heading">Leadott rendeléseket</h1>

    <div class="box-container">

        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders`");
        $select_orders->execute();
        if ($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
        ?>
                <div class="box">
                    <p>Felhasználó id <span><?= $fetch_orders['user_id'];?></span></p>
                    <p>Hirdetést feltették <span><?= $fetch_orders['placed_on'];?></span></p>
                    <p>Név <span><?= $fetch_orders['name'];?></span></p>
                    <p>Email <span><?= $fetch_orders['email'];?></span></p>
                    <p>Telefon <span><?= $fetch_orders['number'];?></span></p>
                    <p>Cim <span><?= $fetch_orders['address'];?></span></p>
                    <p>Termékek <span><?= $fetch_orders['total_products'];?></span> - <span><?= $fetch_orders['product_size'];?></span></p>
                    <p>Teljes összeg <span><?= $fetch_orders['total_price'];?></span> RSD</p>
                    <p>Fizetési folyamat <span><?= $fetch_orders['method'];?></span></p>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?= $fetch_orders['id'];?>">
                        <select name="payment_status" class="drop-down">
                            <option value="" selected disabled><?= $fetch_orders['payment_status'];?></option>
                            <option value="Függőben levő">Függőben levő</option>
                            <option value="Befejezett">Befejezett</option>
                        </select>
                        <div class="flex-btn">
                            <input type="submit" value="Frissités" class="btn" name="update_payment">
                            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Törli a rendelést?');">Törlés</a>
                        </div>
                    </form>

                </div>
        <?php
            }
        }else{
            echo '<p class="empty">Nincs még rendelés!</p>';
        }
        ?>

    </div>


</section>






<script src="../js/admin_script.js"></script>

</body>
</html>