<?php

include '../php/db_config.php';

session_start();

if (isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass =sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_admin=$conn->prepare("SELECT * FROM `admins` WHERE name=? AND password = ?");
    $select_admin->execute([$name, $pass]);

    if ($select_admin->rowCount() > 0){
        $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
        $_SESSION['admin_id'] = $fetch_admin_id['id'];
        header('location:dashboard.php');
    }else{
        $message[] = 'Helytelen felhasználónév vagy jelszó!';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bejelentkezés</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

    <style>
        body{
            background: url("../img/admin-login-bg.jpg");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>



<?php
if (isset($message)){
    foreach ($message as $message){
        echo '
        <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<section class="form-container">

    <form action="" method="post">
        <h3>Jelentkezz be</h3>
        <p>alapértelmezett felhasználónév = <span>admin</span> & jelszó = <span>111</span></p>
        <input type="text" name="name" maxlength="20" required class="box" placeholder="Felhasználónév" oninput="this.value = this.replace(/\s/g, '')">
        <input type="password" name="pass" maxlength="20" required class="box" placeholder="Jelszó" oninput="this.value = this.replace(/\s/g, '')">
        <input type="submit" value="Bejelentkezés" name="submit" class="btn">
    </form>



</section>



</body>
</html>