<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
}

if (isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->execute([$delete_id]);
    header('location:messages.php');
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

<section class="messages">

    <h1 class="heading">Új üzenetek</h1>

    <div class="box-container">

        <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            if ($select_messages->rowCount() > 0){
            while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
        ?>
                <div class="box">
                <p> Felhasználó id : <span><?= $fetch_messages['user_id'];?></span></p>
                <p> Felhasználó neve : <span><?= $fetch_messages['name'];?></span></p>
                <p> Telefon szám : <span><?= $fetch_messages['number'];?></span></p>
                <p> Email : <span><?= $fetch_messages['email'];?></span></p>
                <p> Üzenet : <span><?= $fetch_messages['message'];?></span></p>
                    <a href=messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Törli az üzenetet?');">Törlés</a>
                </div>
                <?php
            }
            }else{
                echo '<p class="empty">Nincs üzenet</p>';
            }
        ?>

    </div>

</section>







<script src="../js/admin_script.js"></script>

</body>
</html>