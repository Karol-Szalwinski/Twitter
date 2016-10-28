<?php

class User {

    private $id;
    private $username;
    private $hashedPassword;
    private $email;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashedPassword = "";
        $this->creationDate = "";
    }

    public function setUsername($newUsername) {
        $this->username = $newUsername;
        return $this;
    }

    public function setPassword($newPassword) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->hashedPassword = $newHashedPassword;
        return $this;
    }

    public function setEmail($newEmail) {
        $this->email = $newEmail;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->hashedPassword;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            //Saving new user to DB
            $sql = "INSERT INTO User(username, email, hashed_password)
            VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE User SET username='$this->username',
                    email='$this->email',
                    hashed_password='$this->hashedPassword'
                    WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param mysqli $connection
     * @param type $id
     * @return \User
     */
    static public
            function loadUserById(mysqli $connection, $id) {
        $sql = "SELECT * FROM User WHERE id=$id";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            $loadedUser->creationDate = $row['creation_date'];
            return $loadedUser;
        }
        return false;
    }

    static public
            function loadAllUsers(mysqli $connection) {
        $sql = "SELECT * FROM User";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];
                $loadedUser->creationDate = $row['creation_date'];
                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    static public
            function emailIsAvailable(mysqli $connection, $email) {
        $sql = "SELECT * FROM User WHERE `email`='$email'";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 0) {
            return true;
        }
        return false;
    }

    public function delete(mysqli $connection) {
        if ($this->id != -1) {
            $sql = "DELETE FROM User WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    static public function loginUser(mysqli $conn, $email, $password) {
        $sql = "SELECT * FROM User WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            //zwracamy wynik jako tabl assocjacyjne, gdzi kluczami sa nazwy kolumn
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['hashed_password'])) {
                return $row['id'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function countTweets(mysqli $connection) {
        $id = $this->id;
        $sql = "SELECT * FROM Tweet WHERE user_id=$id";
        $result = $connection->query($sql);
        return $result->num_rows;
    }

    public function generateUserLink() {
        return "<a href='showUser.php?uid=" . $this->getId() .
                "'><strong>" . $this->getUserName() . "</strong></a>";
    }

    static public function generateUserLinkById($conn, $userId) {
        $userName = User::loadUserById($conn, $userId)->getUsername();
        return "<a href='showUser.php?uid=" . $userId .
                "'><strong>$userName</strong></a>";
    }

}
