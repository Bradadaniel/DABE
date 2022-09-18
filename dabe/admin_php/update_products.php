<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
}

if (isset($_POST['update'])) {

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $size = $_POST['size'];
    $size = filter_var($size, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    $update_product = $conn->prepare("UPDATE `products` SET name = ?, price= ?, details = ?, category = ?, size = ? WHERE id = ?");
    $update_product->execute([$name, $price, $details, $category, $size, $pid]);

    $message[] = 'Termék szerkesztve!';

    $old_image_01 = $_POST['old_image_01'];
    $image_01 = $_FILES['image_01']['name'];
    $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
    $image_01_size = $_FILES['image_01']['size'];
    $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
    $image_01_folder = '../uploaded_img/'.$image_01;

    if (!empty($image_01)) {
        if ($image_01_size > 4000000) {
            $message[] = 'A kép mérete túl nagy!';
        } else {
            $update_image_01 = $conn->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
            $update_image_01->execute([$image_01, $pid]);
            move_uploaded_file($image_01_tmp_name, $image_01_folder);
            unlink('../uploaded_img/'.$old_image_01);
            $message[] = 'Kép 01 frissitve!';
        }
    }

    $old_image_02 = $_POST['old_image_02'];
    $image_02 = $_FILES['image_02']['name'];
    $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
    $image_02_size = $_FILES['image_02']['size'];
    $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
    $image_02_folder = '../uploaded_img/'.$image_02;

    if (!empty($image_02)) {
        if ($image_02_size > 4000000) {
            $message[] = 'A kép mérete túl nagy!';
        } else {
            $update_image_02 = $conn->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
            $update_image_02->execute([$image_02, $pid]);
            move_uploaded_file($image_02_tmp_name, $image_02_folder);
            unlink('../uploaded_img/'.$old_image_02);
            $message[] = 'Kép 02 frissitve!';
        }
    }

        $old_image_03 = $_POST['old_image_03'];
        $image_03 = $_FILES['image_03']['name'];
        $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
        $image_03_size = $_FILES['image_03']['size'];
        $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
        $image_03_folder = '../uploaded_img/'.$image_03;

    if (!empty($image_03)) {
        if ($image_03_size > 4000000) {
            $message[] = 'A kép mérete túl nagy!';
        } else {
            $update_image_03 = $conn->prepare("UPDATE `products` SET image_03 = ? WHERE id = ?");
            $update_image_03->execute([$image_03, $pid]);
            move_uploaded_file($image_03_tmp_name, $image_03_folder);
            unlink('../uploaded_img/'.$old_image_03);
            $message[] = 'Kép 03 frissitve!';
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
    <title>Irányítópult</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">

    <style>
        body{
            background: url("../img/update_product_bg.jpg");
            background-size: cover;
            background-position: center;
        }
    </style>

</head>
<body>

<?php include 'admin_header.php'?>


<section class="update-product">
    <h1 class="heading">Termék szerkesztése</h1>

    <?php
    $update_id = $_GET['update'];
    $show_products= $conn->prepare("SELECT * FROM `products` WHERE id =?");
    $show_products->execute([$update_id]);
    if ($show_products->rowCount() > 0){
    while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){
    ?>

        <form action="" method="post" enctype="multipart/form-data">

            <input type="hidden" name="pid" value="<?= $fetch_products['id'];?>">
            <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01'];?>">
            <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02'];?>">
            <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03'];?>">

            <div class="image-container">
                <div class="main-image">
                    <img src="../uploaded_img/<?= $fetch_products['image_01'];?>" alt="">
                </div>
                <div class="sub-images">
                    <img src="../uploaded_img/<?= $fetch_products['image_01'];?>" alt="">
                    <img src="../uploaded_img/<?= $fetch_products['image_02'];?>" alt="">
                    <img src="../uploaded_img/<?= $fetch_products['image_03'];?>" alt="">
                    </div>
            </div>
            <span>Név szerkesztése</span>
            <input type="text" name="name" required placeholder="Irja be a termék nevét!" class="box" maxlength="100" value="<?= $fetch_products['name'];?>">
            <span>Ár szerkesztése</span>
            <input type="number" min="0" max="9999999" name="price" required placeholder="Irja be a termék árát!(RSD)" class="box" onkeypress="if (this.value.length ==10) return false;" value="<?= $fetch_products['price'];?>">
            <span>Kategória szerkesztése</span>
            <input type="text" name="category" required placeholder="Irja be a termék kategóriáját!" class="box" value="<?= $fetch_products['category'];?>">
            <span>Méretek szerkesztése</span>
            <input type="text" name="size" required placeholder="Irja be a termék méreteit!" class="box" value="<?= $fetch_products['size'];?>">
            <sppan>Leirás szerkesztése</sppan>
            <textarea name="details" class="box" placeholder="Irja le a termék leirását!" required maxlength="500" cols="30" rows="10" ><?= $fetch_products['details'];?></textarea>
            <span>Kép 01 szerkesztése</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
            <span>Kép 02 szerkesztése</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
            <span>Kép 03 szerkesztése</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
            <div class="flex-btn">
                <input type="submit" value="Szerkesztés" class="btn" name="update">
                <a href="products.php" class="option-btn">Vissza</a>
            </div>
        </form>

     <?php
    }
    }else{
        echo '<p class="empty">Nincs még termék hozzáadva!</p>';
    }
    ?>

</section>


<script src="../js/admin_script.js"></script>

</body>
</html>