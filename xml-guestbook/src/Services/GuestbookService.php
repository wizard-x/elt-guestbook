<?php namespace App\Services;

use App\DTO\MessageDTO;
use App\Interfaces\GuestbookInterface;
use App\Tools\SimpleXMLGuestbookParser;

class GuestbookService implements GuestbookInterface {
    var $xml_filename = null;

    public function __construct($source) {
        $this->xml_filename = $source;
    }

    public function getMessagesCount(): int {
        return 0;
    }

    public function getAllMessages(): array {
        return (new SimpleXMLGuestbookParser())->open($this->xml_filename)->findAllMessages();
    }

    public function appendReply(MessageDTO $message): int {
        return (new SimpleXMLGuestbookParser())->open($this->xml_filename)->appendReply($message);
    }

    public function deleteMessage(int $id): void {
        (new SimpleXMLGuestbookParser())->open($this->xml_filename)->deleteMessage($id);
    }

    public function updateMessage(int $id, String $text): void {
        (new SimpleXMLGuestbookParser())->open($this->xml_filename)->updateMessage($id, $text);
    }

    public function getPartialMessages(int $page=0, int $limit=10): array {
        return [];
    }
}