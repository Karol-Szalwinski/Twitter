<?php

class Message {

    private $id;
    private $senderId;
    private $recipientId;
    private $message;
    private $read;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->senderId = -1;
        $this->recipientId = -1;
        $this->message = '';
        $this->read = 0;
        $this->creationDate = 0;
    }

    public function getId() {
        return $this->id;
    }

    public function setSenderId($newSenderId) {
        $this->senderId = is_numeric($newSenderId) ? $newSenderId : -1;
        return $this;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function setRecipientId($newRecipientId) {
        $this->recipientId = is_numeric($newRecipientId) ? $newRecipientId : -1;
        return $this;
    }

    public function getRecipientId() {
        return $this->recipientId;
    }

    public function setMessage($newMessage) {
        $this->message = trim($newMessage);
        return $this;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setRead() {
        $this->read = 1;
        return $this;
    }

    public function getRead() {
        return $this->read;
    }

    public function setCreationDate($newCreationDate) {
        $this->creationDate = $newCreationDate;
        return $this;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    static public function loadMessageById(mysqli $connection, $id) {
        $sql = "SELECT * FROM Message WHERE id=$id ORDER BY creation_date DESC";
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->setSenderId($row['sender_id']);
            $loadedMessage->setRecipientId($row['recipient_id']);
            $loadedMessage->setMessage($row['message']);
            $loadedMessage->setCreationDate($row['creation_date']);
            $loadedMessage->read = $row['read'];
            return $loadedMessage;
        }
        return null;
    }

    static public function loadMessagesBySenderId(mysqli $connection, $userId) {
        $sql = "SELECT * FROM Message WHERE sender_id=$userId ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->setSenderId($row['sender_id']);
                $loadedMessage->setRecipientId($row['recipient_id']);
                $loadedMessage->setMessage($row['message']);
                $loadedMessage->setCreationDate($row['creation_date']);
                $loadedMessage->read = $row['read'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }

    static public function loadMessagesByRecipientId(mysqli $connection, $userId) {
        $sql = "SELECT * FROM Message WHERE recipient_id=$userId ORDER BY creation_date DESC";
        $ret = [];
        $result = $connection->query($sql);
        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->setSenderId($row['sender_id']);
                $loadedMessage->setRecipientId($row['recipient_id']);
                $loadedMessage->setMessage($row['message']);
                $loadedMessage->setCreationDate($row['creation_date']);
                $loadedMessage->read = $row['read'];
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }

    public function saveToDB(mysqli $connection) {
        if ($this->id == -1) {
            //Synchronizuję wiadomości z bazą
            $sql = "INSERT INTO Message(sender_id, recipient_id, message, `read`)
            VALUES ('$this->senderId', '$this->recipientId', '$this->message', '$this->read')";
            $result = $connection->query($sql);
            if ($result == true) {
                $this->id = $connection->insert_id;
                return true;
            }
        } else {
            $sql = "UPDATE Message SET
                    sender_id='$this->senderId',
                    recipient_id='$this->recipientId',   
                    message='$this->message',
                    `read`='$this->read'
                    WHERE id='$this->id'";
            $result = $connection->query($sql);
            if ($result == true) {
                return true;
            }
        }
        return false;
    }

    public function generateMessageLink() {
        $linkText = (strlen($this->message) > 30) ? substr($this->message, 0, 30) . '...' : $this->message;
        $strongNonReadOpenTag = ($this->getRead() == 1) ? '' : '<strong>';
        $strongNonReadCloseTag = ($this->getRead() == 1) ? '' : '</strong>';
        return "<a href='showMessage.php?mid=$this->id'>"
                . "$strongNonReadOpenTag $linkText $strongNonReadCloseTag</a>";
    }

    public function showSendedMessages($connection) {
        echo "<div class='message'>" .
        "<span class='message-author'>" .
        User::generateUserLinkById($connection, $this->getRecipientId()) .
        "</span><span class='message-text'>" .
        $this->generateMessageLink() .
        "</span><span class='message-date'>" .
        $this->getCreationDate() .
        '</span></div>';
    }

    public function showRecipientMessages($connection) {
        echo "<div class='message'>" .
        "<span class='message-author'>" .
        User::generateUserLinkById($connection, $this->getSenderId()) .
        "</span><span class='message-text'>" .
        $this->generateMessageLink() .
        "</span><span class='message-date'>" .
        $this->getCreationDate() .
        '</span></div>';
    }

}
