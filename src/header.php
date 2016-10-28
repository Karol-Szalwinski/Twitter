

<nav class="menu">
    <ul>
        <li>
            <a href="index.php">
                <span><img src="gfx/ico/home.png"></span>
                <span> Główna</span>
            </a>
        </li>
        <li>
            <a href="showMessages.php">
                <span><img src="gfx/ico/message.png"></span>
                <span>Wiadomości</span>
            </a>
        </li>
        <li>
            <a href="showUser.php?uid=<?php echo $loggedUserId; ?>">
                <span><img src="gfx/ico/user.png"></span>
                <span> Mój profil</span>
            </a>
        </li>
        <li>
            <a href="logoutUser.php">
                <span><img src="gfx/ico/log-out.png"></span>
                <span> Wyloguj</span>
            </a>
        </li>
        <li>
            <img id='bird-menu' src='gfx/bird.png'>
        </li>
        <li id="li-left">
            <p>Jesteś zalogowany jako <?php echo $loggedUserName; ?><p>
        </li>
    </ul>
</nav>


