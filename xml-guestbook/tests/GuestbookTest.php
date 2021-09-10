<?php

namespace App\Tests;

use App\DTO\MessageDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use App\Services\GuestbookService;
use App\Tools\SimpleXMLGuestbookParser;
use App\Tools\XMLGuestbookParser;

class GuestbookTest extends KernelTestCase
{
    public function test_is_service_exists(): void
    {
        $kernel = self::bootKernel([
            'debug' => false,
        ]);
        
        try {
            $service = new GuestbookService(getenv('GUESTBOOK_FILENAME'));
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_messages(): void
    {
        $p = new SimpleXMLGuestbookParser();
        $p->open(getenv('GUESTBOOK_FILENAME'));
        $messages = $p->findAllMessages();
        var_dump($messages);
        $this->assertTrue(true);
    }
}
