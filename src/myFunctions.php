<?php

//Funkcja sprawdza czy jestesmy zalogowani, jeśli nie to nas przekierowuje do logowania
//Zwraca też obiekt User
function isLoggedUser($conn) {
    if (!isset($_SESSION['loggedUser'])) {
        $dirname = realpath(__DIR__) . '/..';
        header ('Location: loginUser.php');
    }
    return User::loadUserById($conn, $_SESSION['loggedUser']);
}

//Funkcja wyświetla kod html do wyświetlenia errorów w wybranym miejscu strony
//Zbieram błędy z całej strony, a potem wyświetlam tam gdzie chcę
function printErrors($errorsArray) {
    foreach ($errorsArray as $error) {
        echo "<div class='error'>" . $error . "</div>";
    }
    if (empty($errorsArray)) {
        return false;
    }
    return true;
}
