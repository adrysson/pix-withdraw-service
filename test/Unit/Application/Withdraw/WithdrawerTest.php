<?php

namespace Test\Unit\Application\Withdraw;

use App\Application\Withdraw\Withdrawer;
use App\Domain\EventDispatcher;
use App\Repository\WithdrawalRepository;
use PHPUnit\Framework\TestCase;
use Mockery;
use Test\Stubs\Domain\Entity\WithdrawalStub;

class WithdrawerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testExecuteCallsWithdrawOnRepository(): void
    {
        $withdrawal = WithdrawalStub::random(
            amount: 60.0,
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
        $withdrawal = WithdrawalStub::random(
            amount: 60.0,
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
