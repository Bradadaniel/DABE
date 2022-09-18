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

    $update_name = $conn->prepare("UPDATE `admins` SET name=? WHERE id=?");
    $update_name->execute([$name, $admin_id]);

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $select_old_pass = $conn->prepare("SELECT password FROM `admins` WHERE id = ?");
    $select_old_pass->execute([$admin_id]);
    $fetch_prev_pass= $select_old_pass->fetch(PDO::FETCH_ASSOC);
    $prev_pass =$fetch_prev_pass['password'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = sha1($_POST['confirm_pass']);
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

    if ($old_pass == $empty_pass){
        $message[] = 'Kérem irja me a régi jelszavát!';
    }elseif ($old_pass != $prev_pass){
        $message[]='Helytelen régi jelszó!';
    }elseif ($new_pass != $confirm_pass){
        $message[]='Az új jelszavak nem eggyeznek!';
    }else{
        if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[]='A jelszó sikeresen módositva!';
        }else{
                $message[]= 'Kérem irja be az új jelszavat!';
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
    <title>Profil szerkesztés</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include 'admin_header.php'?>

<section class="form-container">

    <form action="" method="post">
        <h3>Profil módositása</h3>
        <input type="text" name="name" maxlength="20" required class="box" value="<?= $fetch_profile['name'];?>" placeholder="Irja be a felhasználónevét" oninput="this.value = this.replace(/\s/g, '')">
        <input type="password" name="old_pass" maxlength="20" class="box" placeholder="Irja be a régi jelszavát" oninput="this.value = this.replace(/\s/g, '')">
        <input type="password" name="new_pass" maxlength="20" class="box" placeholder="Irja be az új jelszavát" oninput="this.value = this.replace(/\s/g, '')">
        <input type="password" name="confirm_pass" maxlength="20" class="box" placeholder="Erősitse meg az új jelszavát" oninput="this.value = this.replace(/\s/g, '')">
        <input type="submit" value="Szerkesztés" name="submit" class="btn">
    </form>

</section>


<script src="../js/admin_script.js"></script>

</body>
</html>
