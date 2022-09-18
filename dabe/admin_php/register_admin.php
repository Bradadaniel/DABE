<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
}

if (isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass =sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass =sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_admin=$conn->prepare("SELECT * FROM `admins` WHERE name=?");
    $select_admin->execute([$name]);

    if ($select_admin->rowCount() > 0){
        $message[] = 'Felhasználónév már létezik!';
    }else{
        if ($pass != $cpass){
            $message[]= 'A két jelszó nem eggyezik!';
        }
        else{
            $inser_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?,?)");
            $inser_admin->execute([$name, $cpass]);
            $message[] = 'Sikeres regisztráció!';
        }
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
    <title>Regisztráció</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include 'admin_header.php'?>

<section class="form-container">

    <form action="" method="post">
        <h3>Regisztrálj most</h3>
        <input type="text" name="name" maxlength="20" required class="box" placeholder="Irja be a felhasználónevét" oninput="this.value = this.replace(/\s/g, '')">
        <input type="password" name="pass" maxlength="20" required class="box" placeholder="Irja be a jelszavát" oninput="this.value = this.replace(/\s/g, '')">
        <input type="password" name="cpass" maxlength="20" required class="box" placeholder="Erősitse meg a jelszavát" oninput="this.value = this.replace(/\s/g, '')">
        <input type="submit" value="Regisztráció" name="submit" class="btn">
    </form>

</section>








<script src="../js/admin_script.js"></script>

</body>
</html>