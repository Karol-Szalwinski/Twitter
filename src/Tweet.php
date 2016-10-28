<?php

class Tweet {

    private $id;
    private $userId;
    private $tweet;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = -1;
        $this->tweet = '';
        $this->creationDate = -1;
    }

    public function getId() {
        return $this->id;
    }

    public function setUserId($newUserId) {
        $this->userId = is_numeric($newUserId) ? $newUserId : -1;
        return $this;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setTweet($newTweet) {
        $this->tweet = trim($newTweet);
        return $this;
    }

    public function getTweet() {
        return $this->tweet;
    }

    public function setCreationDate($newCreationDate) {
        $this->creationDate = $newCreationDate;
        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    static public
            function loadTweetById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Tweet WHERE id=$id ORDER BY creation_date DESC";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->setUserId($row['user_id']);
            $loadedTweet->setTweet($row['tweet']);
            $loadedTweet->setCreationDate($row['creation_date']);
            return $loadedTweet;
        }
        return null;
    }

    static public
            function loadTweetByUserId(mysqli $connection, $userId) {
        $sql = "SELECT * FROM Tweet WHERE user_id=$userId  ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->setUserId($row['user_id']);
                $loadedTweet->setTweet($row['tweet']);
                $loadedTweet->setCreationDate($row['creation_date']);
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    static public
            function loadAllTweets(mysqli $connection) {
        $sql = "SELECT * FROM Tweet ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->setUserId($row['user_id']);
                $loadedTweet->setTweet($row['tweet']);
                $loadedTweet->setCreationDate($row['creation_date']);
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            $sql = "INSERT INTO Tweet(user_id, tweet)
            VALUES ('$this->userId', '$this->tweet')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE Tweet SET user_id='$this->userId',
                    tweet='$this->tweet',
                    WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return true;
            }
        }
        return false;
    }

    public function countComments(mysqli $connection) {
        $id = $this->id;
        $sql = "SELECT * FROM Comment WHERE tweet_id=$id";
        $result = $connection->query($sql);
        return $result->num_rows;
        
    }

    public function showTweet($connection) {
        echo "<div class='tweet'>";
        echo User::generateUserLinkById($connection, $this->getUserId());
        echo " . " . "{$this->getCreationDate()}";
        echo "<br><a href='showTweet.php?tid=" . $this->getId() .
        "'>" . $this->getTweet() . "</a><br>";
        echo "Komentarzy: {$this->countComments($connection)}";
        echo '</div>';
    }

}
