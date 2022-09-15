<?php

include '../php/db_config.php';

session_start();

$admin_id =  $_SESSION['admin_id'];

if (!isset($admin_id)){
    header('location:admin_login.php');
};

if (isset($_POST['add_product'])){

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

    $image_01 = $_FILES['image_01']['name'];
    $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
    $image_01_size = $_FILES['image_01']['size'];
    $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
    $image_01_folder = '../uploaded_img/'.$image_01;

    $image_02 = $_FILES['image_02']['name'];
    $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
    $image_02_size = $_FILES['image_02']['size'];
    $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
    $image_02_folder = '../uploaded_img/'.$image_02;

    $image_03 = $_FILES['image_03']['name'];
    $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
    $image_03_size = $_FILES['image_03']['size'];
    $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
    $image_03_folder = '../uploaded_img/'.$image_03;

    $select_product = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_product->execute([$name]);

    if ($select_product->rowCount() > 0){
        $message[] ='A terméknév már létezik!';
    }else{
        if ($image_01_size > 4000000 OR $image_02_size > 4000000 OR $image_03_size > 4000000){
            $message[]='A kép mérete túl nagy!';
        }else{
            move_uploaded_file($image_01_tmp_name, $image_01_folder);
            move_uploaded_file($image_02_tmp_name, $image_02_folder);
            move_uploaded_file($image_03_tmp_name, $image_03_folder);

            $insert_product = $conn->prepare("INSERT INTO `products`(name, details, price, category, size, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?,?)");
            $insert_product->execute([$name, $details, $price, $category, $size, $image_01, $image_02, $image_03]);

            $message[]='Új termék hozzáadva!';

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

</head>
<body>

<?php include 'admin_header.php'?>

<section class="add-products">

    <h1 class="heading">Termék hozzáadása</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
                <span>Termék neve (kötelező)</span>
                <input type="text" name="name" required placeholder="Irja be a termék nevét!" class="box" maxlength="100">
            </div>
            <div class="inputBox">
                <span>Termék ára (kötelező)</span>
                <input type="number" min="0" max="9999999" name="price" required placeholder="Irja be a termék árát!(RSD)" class="box" onkeypress="if (this.value.length ==10) return false;">
            </div>
            <div class="inputBox">
                <span>Termék kategóriája (kötelező)</span>
                <input type="text" name="category" required placeholder="Irja be a termék kategóriáját!" class="box"">
            </div>
            <div class="inputBox">
                <span>Elérhető méret (kötelező)</span>
                <input type="text" name="size" required placeholder="Irja be a termék méreteit!" class="box"">
            </div>
            <div class="inputBox">
                <span>Kép 01 (kötelező)</span>
                <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>
            <div class="inputBox">
                <span>Kép 02 (kötelező)</span>
                <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>
            <div class="inputBox">
                <span>Kép 03 (kötelező)</span>
                <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>
            <div class="inputBox">
                <span>Termék leirása</span>
                <textarea name="details" class="box" placeholder="Irja le a termék leirását!" required maxlength="500" cols="30" rows="10"></textarea>
                <input type="submit" value="Termék hozzáadás" name="add_product" class="btn">
            </div>
        </div>
    </form>
</section>


<section class="show-products">

    <div class="box-container">

        <?php
            $show_products= $conn->prepare("SELECT * FROM `products`");
            $show_products->execute();
            if ($show_products->rowCount() > 0){
                while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){
        ?>
                    <div class="box">
                        <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                        <div class="category">(<?= $fetch_products['category'];?>)</div>
                        <div class="name"><?= $fetch_products['name'];?></div>
                        <div class="price"><?= $fetch_products['price'];?> RSD</div>
                        <div class="details"><?= $fetch_products['details'];?></div>
                        <div class="flex-btn">
                            <a href="update_products.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Szerkesztés</a>
                            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Törli a terméket?');">Törlés</a>
                        </div>
                    </div>

<!--                    <div class="cards">-->
<!--                        <img src="../uploaded_img/--><?//= $fetch_products['image_01']; ?><!--" alt="">-->
<!--                        <div class="text-container">-->
<!--                            <h6>--><?//=$fetch_products['price']; ?><!-- RSD</h6>-->
<!--                            <div class="flat-name">-->
<!--                                <div class="flat-name-loc">-->
<!--                                    <h6>--><?//= $fetch_products['category'];?><!--</h6>-->
<!--                                    <h5>--><?//= $fetch_products['name'];?><!--</h5>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

        <?php
                }
            }else{
                echo '<p class="empty">Nincs még termék hozzáadva!</p>';
            }
        ?>

    </div>

</section>







<script src="../js/admin_script.js"></script>

</body>
</html>