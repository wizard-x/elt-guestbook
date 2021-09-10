<?php namespace App\Interfaces;

use App\DTO\MessageDTO;

interface GuestbookParserInterface {
    public function open($source): self;
    public function findAllMessages(): array;
    public function appendReply(MessageDTO $message): int;
    public function deleteMessage(int $id): void;
    public function updateMessage(int $id, string $text): void;
    public function transformToMessageDTO(array $message): MessageDTO;
}