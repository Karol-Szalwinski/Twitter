
<section class="sidebar">
    <h3><?php echo $sidebarUser->generateUserLink(); ?> </h3>
    <h4>e-mail <?php echo $sidebarUser->getEmail(); ?></h4>
    <p> Dołączył <?php echo $sidebarUser->getCreationDate(); ?></p>
    <p> Tweetów <?php echo $sidebarUser->countTweets($conn); ?></p>
    <!--Buttony do tweetowania lub do edycji danych usera-->
    <ul>
        <?php
        if ($sidebarUser != $loggedUser) {
            echo "<a href='sendMessage.php?uid={$sidebarUser->getId()}'>"
            . "<li class='button2'>Tweetnij do</li></a>";
        }
        if (isset($_GET['uid']) && $_GET['uid'] == $loggedUserId) {
            echo "<a href='editUser.php'><li class='button2'>"
            . "Edytuj swój profil</li></a>";
        }
        ?>
    </ul>
    <img id='bird-sidebar' src="gfx/bird.png">
</section>
