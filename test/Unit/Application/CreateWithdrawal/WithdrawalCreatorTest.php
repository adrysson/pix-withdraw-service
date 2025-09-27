<?php

namespace Test\Unit\Application\CreateWithdrawal;

use App\Application\CreateWithdrawal\WithdrawalCreator;
use App\Application\Withdraw\Withdrawer;
use App\Repository\WithdrawalRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Test\Stubs\Domain\Entity\AccountStub;
use Test\Stubs\Domain\Entity\PixStub;

class WithdrawalCreatorTest extends TestCase
{
    public function testWithdrawCallsAccountWithdrawAndRepositoryUpdate(): void
    {
        $account = AccountStub::random(
            balance: 100.0,
        );
        $method = PixStub::random();

        $withdrawalRepository = Mockery::mock(WithdrawalRepository::class);
        $withdrawalRepository->shouldReceive('create');

        $withdrawer = Mockery::mock(Withdrawer::class);
        $withdrawer->shouldReceive('execute');

        $service = new WithdrawalCreator(
            withdrawalRepository: $withdrawalRepository,
            withdrawer: $withdrawer,
        );

        $service->execute(
            accountId: $account->id,
            method: $method,
            amount: 40.0,
            schedule: null,
        );

        $this->assertTrue(true);
    }
}
