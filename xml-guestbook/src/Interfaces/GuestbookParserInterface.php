<?php namespace App\Interfaces;

use App\DTO\MessageDTO;

interface GuestbookParserInterface {
    public function open($source): self;
    public function findAllMessages(): array;
    public function appendReply(MessageDTO $message): int;
    public function transformToMessageDTO(array $message): MessageDTO;
}