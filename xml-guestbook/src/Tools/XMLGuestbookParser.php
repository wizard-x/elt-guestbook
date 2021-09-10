<?php namespace App\Tools;

use App\DTO\MessageDTO;
use App\Interfaces\GuestbookParserInterface;

// недодумано, недоделано
class XMLGuestbookParser implements GuestbookParserInterface {
    
    public function __construct() {
        $this->reader = new \XMLReader();
    }

    public function open($filename): self {
        $this->reader->open($filename);
        return $this;
    }

    public function findAllMessages(): array {
        $messages = [];
        $tag = 'message';

        while ($this->reader->read() && $this->reader->name !== $tag);

        while ($this->reader->name == $tag) {
            $node = new \SimpleXMLElement($this->reader->readOuterXML());
            $node = $this->transformObjectToArray($node);
            $node = $this->substituteEmptyObjectsByNull($node);
            $id = $node['id'];
            $messages[$id] = $this->transformToMessageDTO($node);
            $this->reader->next($tag);
        }
        return $messages;
    }

    public function appendReply(MessageDTO $message): int {
        return 0;
    }

    public function deleteMessage(int $id): void {
    }

    public function updateMessage(int $id, string $text): void {
    }

    protected function substituteEmptyObjectsByNull(array $node): array {
        return array_reduce(array_keys($node), function($carry, $key) use ($node) {
            $carry[$key] = empty($node[$key]) ? null : $node[$key];
            return $carry;
        }, []);
    }

    protected function transformObjectToArray($object): array {
        return json_decode(json_encode($object), true);
    }

    public function transformToMessageDTO(array $message): MessageDTO {
        $dto = new MessageDTO();
        return $dto
            ->setId($message['id'])
            ->setPublished($message['published'])
            ->setAuthor($message['author'])
            ->setText($message['text'])
            ->setRepliedTo($message['repliedTo'])
        ;
    }
}
