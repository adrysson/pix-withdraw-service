<?php

namespace Test\Unit\Application\Withdraw;

use App\Application\Withdraw\Withdrawer;
use App\Domain\Entity\Pix;
use App\Domain\Entity\Withdrawal;
use App\Domain\EventDispatcher;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Pix\PixId;
use App\Repository\WithdrawalRepository;
use DateTime;
use PHPUnit\Framework\TestCase;
use Mockery;

class WithdrawerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testExecuteCallsWithdrawOnRepository(): void
    {
        $accountId = AccountId::generate();
        $method = new Pix(
            id: PixId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $withdrawal = Withdrawal::create(
            accountId: $accountId,
            method: $method,
            amount: 60.0,
            schedule: null,
        );

        $repository = Mockery::mock(WithdrawalRepository::class);
        $repository->shouldReceive('withdraw')
            ->once()
            ->with($withdrawal);

        $eventDispatcher = Mockery::mock(EventDispatcher::class);
        $eventDispatcher->shouldReceive('dispatch');

        $withdrawer = new Withdrawer($repository, $eventDispatcher);
        $withdrawer->execute($withdrawal);
        $this->assertTrue(true);
    }

    public function testExecuteCallsFinishWithdrawalOnException(): void
    {
        $accountId = AccountId::generate();
        $method = new Pix(
            id: PixId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $withdrawal = Withdrawal::create(
            accountId: $accountId,
            method: $method,
            amount: 60.0,
            schedule: null,
        );
        $exception = new \Exception('fail');
        $repository = Mockery::mock(WithdrawalRepository::class);
        $repository->shouldReceive('withdraw')
            ->andThrow($exception);
        $repository->shouldReceive('finish')
            ->once()
            ->with($withdrawal, $exception);

        $eventDispatcher = Mockery::mock(\App\Domain\EventDispatcher::class);
        $eventDispatcher->shouldNotReceive('dispatch');

        $withdrawer = new Withdrawer($repository, $eventDispatcher);
        $withdrawer->execute($withdrawal);
        $this->assertTrue(true);
    }
}
