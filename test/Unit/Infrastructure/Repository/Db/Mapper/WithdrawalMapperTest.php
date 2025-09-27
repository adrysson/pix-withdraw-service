<?php

namespace Test\Unit\Infrastructure\Repository\Db\Mapper;

use App\Infrastructure\Repository\Db\Mapper\WithdrawalMapper;
use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\Pix;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Pix\PixId;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use DateTime;
use PHPUnit\Framework\TestCase;

class WithdrawalMapperTest extends TestCase
{
    public function testMapWithdrawalWithSchedule(): void
    {
        $row = (object) [
            'id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
            'account_id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
            'amount' => 100.0,
            'scheduled' => true,
            'scheduled_for' => '2023-01-01 12:00:00',
            'done' => false,
            'created_at' => '2023-01-01 10:00:00',
            'updated_at' => '2023-01-01 11:00:00',
        ];
        $method = new Pix(
            new PixId('c1d2e3f4-5678-1234-9abc-def012345678'),
            new EmailPixKey('user@example.com'),
            new DateTime('2023-01-01 09:00:00'),
            new DateTime('2023-01-01 09:30:00')
        );

        $withdrawal = WithdrawalMapper::mapWithdrawal($row, $method);

        $this->assertInstanceOf(Withdrawal::class, $withdrawal);
        $this->assertEquals(new WithdrawalId('c1d2e3f4-5678-1234-9abc-def012345678'), $withdrawal->id);
        $this->assertEquals(new AccountId('c1d2e3f4-5678-1234-9abc-def012345678'), $withdrawal->accountId);
        $this->assertEquals($method, $withdrawal->method);
        $this->assertEquals(100.0, $withdrawal->amount);
        $this->assertInstanceOf(WithdrawalSchedule::class, $withdrawal->schedule);
        $this->assertEquals('2023-01-01 12:00:00', $withdrawal->schedule->value->format('Y-m-d H:i:s'));
        $this->assertFalse($withdrawal->done());
        $this->assertEquals('2023-01-01 10:00:00', $withdrawal->createdAt->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-01 11:00:00', $withdrawal->updatedAt()->format('Y-m-d H:i:s'));
    }

    public function testMapWithdrawalWithoutSchedule(): void
    {
        $row = (object) [
            'id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
            'account_id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
            'amount' => 50.0,
            'scheduled' => false,
            'scheduled_for' => null,
            'done' => true,
            'created_at' => '2023-01-02 10:00:00',
            'updated_at' => '2023-01-02 11:00:00',
        ];
        $method = new Pix(
            new PixId('c1d2e3f4-5678-1234-9abc-def012345678'),
            new EmailPixKey('user2@example.com'),
            new DateTime('2023-01-02 09:00:00'),
            new DateTime('2023-01-02 09:30:00')
        );

        $withdrawal = WithdrawalMapper::mapWithdrawal($row, $method);

        $this->assertInstanceOf(Withdrawal::class, $withdrawal);
        $this->assertEquals(new WithdrawalId('c1d2e3f4-5678-1234-9abc-def012345678'), $withdrawal->id);
        $this->assertEquals(new AccountId('c1d2e3f4-5678-1234-9abc-def012345678'), $withdrawal->accountId);
        $this->assertEquals($method, $withdrawal->method);
        $this->assertEquals(50.0, $withdrawal->amount);
        $this->assertNull($withdrawal->schedule);
        $this->assertTrue($withdrawal->done());
        $this->assertEquals('2023-01-02 10:00:00', $withdrawal->createdAt->format('Y-m-d H:i:s'));
        $this->assertEquals('2023-01-02 11:00:00', $withdrawal->updatedAt()->format('Y-m-d H:i:s'));
    }
}
