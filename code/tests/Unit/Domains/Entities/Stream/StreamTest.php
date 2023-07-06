<?php
namespace Test\Unit\Domains\Entities\Stream;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Travis\StreamingBackend\Domains\Entities\Stream\Stream;
use Travis\StreamingBackend\Domains\Entities\User\User;

class StreamTest extends TestCase
{
    public function testGetters()
    {
        $user = $this->createMock(User::class);
        $stream = new Stream(
            title: "title",
            user: $user
        );
        $this->assertEquals("title", $stream->getTitle());
        $this->assertEquals($user, $stream->getUser());
        $this->assertNotNull($stream->getUuid());
        $this->assertNotNull($stream->getCreatedAt());
        $this->assertNotNull($stream->getUpdatedAt());
    }

    public function testSetters()
    {
        $user = $this->createMock(User::class);
        $stream = new Stream(
            title: "title",
            user: $user
        );
        $stream->setTitle("new title");
        $this->assertEquals("new title", $stream->getTitle());
    }
}