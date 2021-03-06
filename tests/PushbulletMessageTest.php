<?php

namespace NotificationChannels\Pushbullet\Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use NotificationChannels\Pushbullet\PushbulletMessage;
use NotificationChannels\Pushbullet\Targets\Targetable;

class PushbulletMessageTest extends TestCase
{
    /** @test */
    public function message_can_be_instantiated_with_text()
    {
        $message = new PushbulletMessage('Hello');

        $this->assertEquals('Hello', $message->message);
    }

    /** @test */
    public function new_message_is_of_note_type()
    {
        $message = new PushbulletMessage('Hello');

        $this->assertEquals(PushbulletMessage::TYPE_NOTE, $message->type);
    }

    /** @test */
    public function message_can_be_set_to_link_type()
    {
        $message = new PushbulletMessage('Hello');

        $message->link();

        $this->assertEquals(PushbulletMessage::TYPE_LINK, $message->type);
    }

    /** @test */
    public function message_can_be_set_to_note_type()
    {
        $message = new PushbulletMessage('Hello');

        $message->link();

        $message->note();

        $this->assertEquals(PushbulletMessage::TYPE_NOTE, $message->type);
    }

    /** @test */
    public function message_can_have_title_set()
    {
        $message = new PushbulletMessage('Hello');

        $message->title('Title');

        $this->assertEquals('Title', $message->title);
    }

    /** @test */
    public function message_can_have_message_set()
    {
        $message = new PushbulletMessage('Hello');

        $message->message('Different message');

        $this->assertEquals('Different message', $message->message);
    }

    /** @test */
    public function message_can_have_url_set()
    {
        $message = new PushbulletMessage('Hello');

        $message->url('http://example.com');

        $this->assertEquals('http://example.com', $message->url);
    }

    /** @test */
    public function it_can_be_cast_to_array()
    {
        $message = new PushbulletMessage('Message');

        /** @var MockObject|Targetable $target */
        $target = $this->createMock(Targetable::class);
        $target->expects($this->once())
            ->method('getTarget')
            ->willReturn(['tag' => 'xcv']);

        $message
            ->title('Hello')
            ->target($target);

        $this->assertEquals(
            [
                'type' => 'note',
                'title' => 'Hello',
                'body' => 'Message',
                'tag' => 'xcv',
            ],
            $message->toArray()
        );
    }
}
