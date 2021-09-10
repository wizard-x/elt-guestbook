<?php namespace App\Tools;

use App\DTO\MessageDTO;
use App\Interfaces\GuestbookParserInterface;
use SimpleXMLElement;

class SimpleXMLGuestbookParser implements GuestbookParserInterface {
    private $filename = '';

    public function open($filename): self {
        $this->filename = $filename;
        return $this;
    }

    public function findAllMessages(): array {
        $messages = [];
        if (strlen($this->filename) > 0) {
            $data = \simplexml_load_file($this->filename);
            $_messages = $data->xpath('*/message');
            foreach ($_messages as $node) {
                $node = $this->transformObjectToArray($node);
                $node = $this->substituteEmptyObjectsByNull($node);
                $id = $node['id'];
                $messages[$id] = $this->transformToMessageDTO($node);
            }
        }
        return $this->replies($messages);
    }

    public function replies(array $messages): array {
        $unsetKeys = [];

        foreach ($messages as $key => &$message) {
            $repliedMessageId = $message->getRepliedTo();
            if (is_null($repliedMessageId)) {
                continue;
            }
            $messages[$repliedMessageId]->addReply($message);
            $unsetKeys[] = $key;
        }

        array_map(function ($key) use (&$messages) {
            unset($messages[$key]);
        }, $unsetKeys);

        return $messages;
    }

    public function appendReply(MessageDTO $message): int {
        $xml = \simplexml_load_file($this->filename);
        $xml->config->messages++;
        $m = $xml->messages->addChild('message');
        $m->addChild('id', $xml->config->messages);
        $m->addChild('repliedTo', $message->getRepliedTo());
        $m->addChild('published', $message->getPublished());
        $m->addChild('author', $message->getAuthor());
        $m->addChild('text', $message->getText());
        $xml->asXML($this->filename);
        return strval($xml->config->messages);
    }

    public function deleteMessage(int $id): void {
        $xml = \simplexml_load_file($this->filename);
        $toBeDeleted = [$id];
        $messages = $xml->messages->message;
        $count = count($messages);
        for ($i=0; $i<$count; ++$i) {
            if (is_null($messages[$i])) {
                continue;
            }
            if (in_array($messages[$i]->id, $toBeDeleted) || in_array($messages[$i]->repliedTo, $toBeDeleted)) {
                $messages[$i]->id != $id && strlen($messages[$i]->repliedTo) > 0 && array_push($toBeDeleted, (int)$messages[$i]->id);
                unset($messages[$i]);
                // $xml->config->messages--;
                --$i;
            }
        }
        $xml->asXML($this->filename);
    }

    public function updateMessage(int $id, string $text): void {
        $xml = \simplexml_load_file($this->filename);
        $found = $xml->xpath('//messages/message[id="'.$id.'"]');
        if (count($found)) {
            $found[0]->text = $text;
        }
        $xml->asXML($this->filename);
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

    public function transformToMessageDTO($message): MessageDTO {
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
