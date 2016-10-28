<?php
session_start();
//Jeżeli jest w sesji ustawiona jakakolwiek wartość loggedUser to ją usuwamy
if(isset($_SESSION['loggedUser'])){
    unset($_SESSION['loggedUser']);
}
//powrót na główną
header("Location: index.php");
