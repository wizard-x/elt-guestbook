<?php namespace App\Interfaces;

use App\DTO\MessageDTO;

interface GuestbookInterface {
    public function __construct($source);
    public function getAllMessages(): array;
    public function getPartialMessages(int $page=0, int $limit=10): array;
    public function appendReply(MessageDTO $message): int;
    public function deleteMessage(int $id): void;
    public function updateMessage(int $id, string $text): void;
}