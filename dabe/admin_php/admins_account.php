<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
}

if (isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $delete_admin = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
    $delete_admin->execute([$delete_id]);
    header('location:admins_account.php');
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

    <h1 class="heading">Adminok</h1>

    <div class="box-container">

        <div class="box">
            <p>Új admin regisztrálása</p>
            <a href="register_admin.php" class="option-btn">Regisztráció</a>
        </div>

        <?php
            $select_account = $conn->prepare("SELECT * FROM `admins`");
            $select_account->execute();
            if ($select_account->rowCount() > 0){
            while ($fetch_accounts= $select_account->fetch(PDO::FETCH_ASSOC)){
        ?>
                <div class="box">
                    <p> Admin id : <span><?= $fetch_accounts['id'];?></span> </p>
                    <p> Admin neve : <span><?= $fetch_accounts['name'];?></span> </p>
                    <div class="flex-btn">
                        <a href="admins_account.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Törli a fiókot?');">Törlés</a>
                        <?php
                            if ($fetch_accounts['id']== $admin_id){
                                echo '<a href="update_profile.php" class="option-btn">Szerkesztés</a>';
                            }
                        ?>
                    </div>
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