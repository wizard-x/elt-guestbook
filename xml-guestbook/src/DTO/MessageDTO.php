<?php namespace App\DTO;

class MessageDTO {
    public $id;
    public $repliedTo;
    public $published;
    public $author;
    public $text;
    public $replies = [];

    public function setReplies(array $replies): self {
        $this->replies = $replies;
        return $this;
    }

    public function getReplies(): array {
        return $this->replies;
    }

    public function addReply(MessageDTO &$reply): self {
        $messageId = $reply->getId();
        $this->replies[$messageId] = &$reply;
        return $this;
    }

    public function setId($id): self {
        $this->id = $id;
        return $this;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setRepliedTo($repliedTo): self {
        $this->repliedTo = $repliedTo;
        return $this;
    }

    public function getRepliedTo() {
        return $this->repliedTo;
    }

    public function setPublished($published): self {
        $this->published = $published;
        return $this;
    }

    public function getPublished(): string {
        return $this->published;
    }

    public function setAuthor($author): self {
        $this->author = $author;
        return $this;
    }

    public function getAuthor(): string {
        return $this->author;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function getText(): string {
        return $this->text;
    }

}