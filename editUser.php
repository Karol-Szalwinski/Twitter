<?php
require_once dirname(__FILE__) . '/src/requiredFiles.php';

//Ustalamy id, name i email zalogowanego usera
$loggedUser = isLoggedUser($conn);
$loggedUserId = $loggedUser->getId();
$loggedUserName = $loggedUser->getUsername();
$loggedUserEmail = $loggedUser->getEmail();
$errors1 = $errors2 = [];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit User</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
       <!-----------Nagłówek z menu-------------->
        <header>
            <?php require_once (dirname(__FILE__) . '/src/header.php'); ?>
        </header>

       <!—-----------Panel boczny --------------->
        <aside>
            <?php
            $sidebarUser = $loggedUser;
            require_once (dirname(__FILE__) . '/src/sidebar.php');
            ?>
        </aside>

       <!—-----------Główna treść --------------->
        <main>

            <?php
            //sprawdzam co user wpisał w formularz user-data
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user-data'])) {

                //sprawdzam przesłaną nazwę i trimuję
                if (isset($_POST['user-name']) && strlen(trim($_POST['user-name'])) > 0) {
                    $userName = trim($_POST['user-name']);
                } else {
                    $errors1[] = 'Podałeś nieprawidłowe imię użytkownika';
                }

                //sprawdzam przesłany e-mail, jego długość po usunięciu białych znaków
                if (isset($_POST['user-email']) && strlen(trim($_POST['user-email'])) > 5) {
                    $userEmail = trim($_POST['user-email']);
                    //sprawdzam czy mail jest już w bazie wbudowaną funkcją
                    if (!User::emailIsAvailable($conn, $userEmail) && $userEmail != $loggedUserEmail) {
                        $errors1[] = "Podany e-mail " . $userEmail . " jest już zajęty";
                    }
                } else {
                    $errors1[] = 'Podałeś nieprawidłowy e-mail';
                }
                if (empty($errors1)) {
                    $errors1[] = "Dane zostały pomyślnie zmienione";
                    $loggedUser->setUsername($userName)->setEmail($userEmail)->saveToDB($conn);
                    header('refresh: 3;');
                }
            }
            //Sprawdzam co user podał w drugim formularzu
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
                
                //sprawdzam hasło, jego długość, obcinam białe znaki
                if (isset($_POST['user-password']) && strlen(trim($_POST['user-password'])) > 3) {
                    $userPassword = trim($_POST['user-password']);
                    
                    //sprawdzam czy hasło zgadza się w obydwu polach
                    if (isset($_POST['user-confirm-password']) && trim($_POST['user-confirm-password']) == $userPassword) {
                        $userConfirmPassword = trim($_POST['user-confirm-password']);
                    } else {
                        $errors2[] = 'Podane hasła nie zgadzają się';
                    }
                } else {
                    $errors2[] = 'Podane hasło musi mieć co najmniej 3 znaków';
                }
                
                //Sprawdzam poprawność starego hasła
                if (!isset($_POST['user-old-password']) || 
                        !User::loginUser($conn, $loggedUserEmail, $_POST['user-old-password'])) {
                    $errors2[] = 'Podałeś złe hasło potwierdzające';
                }

                //Jeżeli wszystkie powyższe dane zwalidowały się poprawnie poprawiamy je w bazie
                if (empty($errors2)) {
                    $errors2[] = 'Hasło zostało pomyślnie zmienione';
                    $loggedUser->setPassword($userPassword)->saveToDB($conn);
                    header('refresh: 3;');
                }
            }
            ?>

            <form action=# method="POST">
                <fieldset class="message">
                    <legend><h3>Zmień swoje dane</h3></legend>
                    <?php printErrors($errors1); ?>
                    <label>
                        <p>Imię:</p>
                        <input class="input-short" type="text" maxlengt="20"
                               name="user-name" value="<?php echo $loggedUserName ?>"><br>
                    </label>
                    <label>
                        <p>E-mail:</p>
                        <input class="input-short" type="text" maxlengt="30"
                               name="user-email" value="<?php echo $loggedUserEmail ?>"><br>
                    </label>
                    <br>
                    <input class='button'  type="submit" value="Zapisz" name="user-data">
                    <input class='button'  type="reset" value="Cofnij zmiany">
                </fieldset>
            </form>
            <form action=# method="POST">
                <fieldset class="message">
                    <legend><h3>Zmień hasło</h3></legend>
                    <?php printErrors($errors2); ?>
                    <label>
                        <p>Nowe hasło</p>
                        <input class="input-short" type="password" maxlengt="30"
                               name="user-password" placeholder="Podaj hasło"><br>
                    </label>
                    <label>
                        <p>Potwierdź nowe hasło</p>
                        <input class="input-short" type="password" maxlengt="30"
                               name="user-confirm-password" placeholder="Powtórz hasło"><br>
                    </label>
                    <br>
                    <label>
                        <p>Wprowadź stare hasło</p>
                        <input class="input-short" type="password" maxlengt="30"
                               name="user-old-password" placeholder="Podaj dotychczasowe hasło"><br>
                    </label>

                    <br>
                    <input class='button'  type="submit" value="Zapisz nowe hasło" name="password">
                    <input class='button'  type="reset" value="Cofnij zmiany">
                </fieldset>
            </form>
        </main>
       
       <!—--------------Stopka------------------->
        <footer>
            <?php require_once (dirname(__FILE__) . '/src/footer.php'); ?>   
        </footer>
    </body>
</html>
<?php



