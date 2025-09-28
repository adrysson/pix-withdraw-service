<?php

namespace Test\Unit\Infrastructure\Repository\Db\Mapper;

use App\Infrastructure\Repository\Db\Mapper\PixMapper;
use App\Domain\Enum\PixKeyType;
use App\Domain\ValueObject\Pix\EmailPixKey;
use PHPUnit\Framework\TestCase;

class PixMapperTest extends TestCase
{
    public function testMapPixReturnsPixObject(): void
    {
        $data = (object) [
            'id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
            'account_withdraw_id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
            'type' => PixKeyType::EMAIL->value,
            'key' => 'user@example.com',
            'created_at' => '2023-01-01 10:00:00',
            'updated_at' => '2023-01-01 11:00:00',
        ];

        $pix = PixMapper::mapPix($data);

        $this->assertEquals('c1d2e3f4-5678-1234-9abc-def012345678', $pix->id->value);
        $this->assertEquals('c1d2e3f4-5678-1234-9abc-def012345678', $pix->withdrawalId->value);
        $this->assertInstanceOf(EmailPixKey::class, $pix->key);
        $this->assertEquals('user@example.com', $pix->key->value);
        $this->assertEquals('2023-01-01 10:00:00', $pix->createdAt->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 11:00:00', $pix->updatedAt()->format('Y-m-d H:i:s'));
    }
}
