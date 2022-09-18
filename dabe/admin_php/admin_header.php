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

<header class="header">

    <section class="flex">

        <a href="dashboard.php" class="logo"><span>DABE</span> Shop</a>

        <nav class="navbar">
            <a href="dashboard.php">Kezdőoldal</a>
            <a href="products.php">Termékek</a>
            <a href="placed_orders.php">Rendelések</a>
            <a href="admins_account.php">Adminok</a>
            <a href="users_account.php">Felhasználók</a>
            <a href="messages.php">Üzenetek</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile= $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><?= $fetch_profile['name'];?></p>
            <a href="update_profile.php" class="btn">Profil szerkesztése</a>
            <div class="flex-btn">
                <a href="admin_login.php" class="option-btn">Bejel.</a>
                <a href="register_admin.php" class="option-btn">Regisz.</a>
            </div>
            <a href="admin_logout.php" onclick="return confirm('Biztosan kijelentkezik?')" class="delete-btn">Kijelentkezés</a>
        </div>



    </section>

</header>