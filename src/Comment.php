<?php

class Comment {

    private $id;
    private $userId;
    private $tweetId;
    private $comment;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->tweetId = -1;
        $this->userId = -1;
        $this->comment = '';
        $this->creationDate = -1;
    }

    public function setUserId($newUserId) {
        $this->userId = is_numeric($newUserId) ? $newUserId : -1;
        return $this;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setTweetId($newTweetId) {
        $this->tweetId = is_numeric($newTweetId) ? $newTweetId : -1;
        return $this;
    }

    public function getTweetId() {
        return $this->tweetId;
    }

    public function setComment($newComment) {
        $this->comment = trim($newComment);
        return $this;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setCreationDate($newCreationDate) {
        $this->creationDate = $newCreationDate;
        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    static public
            function loadCommentById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Comment WHERE id=$id ORDER BY creation_date DESC";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->setUserId($row['user_id']);
            $loadedComment->setTweetId($row['tweet_id']);
            $loadedComment->setComment($row['comment']);
            $loadedComment->setCreationDate($row['creation_date']);
            return $loadedComment;
        }
        return null;
    }

    static public
            function loadCommentByTweetId(mysqli $connection, $tweetId) {
        $sql = "SELECT * FROM Comment WHERE tweet_id=$tweetId ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->setUserId($row['user_id']);
                $loadedComment->setTweetId($row['tweet_id']);
                $loadedComment->setComment($row['comment']);
                $loadedComment->setCreationDate($row['creation_date']);
                $ret[] = $loadedComment;
            }
        }
        return $ret;
    }

    static public
            function loadAllComments(mysqli $connection) {
        $sql = "SELECT * FROM Comment ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->setUserId($row['user_id']);
                $loadedComment->setTweetId($row['tweet_id']);
                $loadedComment->setComment($row['comment']);
                $loadedComment->setCreationDate($row['creation_date']);
                $ret[] = $loadedComment;
            }
        }
        return $ret;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            //Saving new user to DB
            $sql = "INSERT INTO Comment(tweet_id, user_id, comment)
            VALUES ('$this->tweetId','$this->userId', '$this->comment')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE Comment SET tweet_id='$this->tweetId',
                    user_id='$this->userId',
                    comment='$this->comment',
                    WHERE id=$this->id";
            $result = $connection->query($sql);
            if ($result == true) {
                return true;
            }
        }
        return false;
    }
    
    public function showComment($connection) {
        echo "<div class='tweet'>";
        echo User::generateUserLinkById($connection, $this->getUserId());
        echo " . " . $this->getCreationDate() . "<br>" . $this->getComment();
        echo '</div>';
    }

}
