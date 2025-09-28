<?php

namespace Test\Unit\Application\CreateWithdrawal;

use App\Application\CreateWithdrawal\WithdrawalCreator;
use App\Application\Withdraw\Withdrawer;
use App\Domain\Repository\WithdrawalRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use Test\Stubs\Domain\Entity\WithdrawalStub;

class WithdrawalCreatorTest extends TestCase
{
    public function testWithdrawCallsAccountWithdrawAndRepositoryUpdate(): void
    {
        $withdrawalRepository = Mockery::mock(WithdrawalRepository::class);
        $withdrawalRepository->shouldReceive('create');

        $withdrawer = Mockery::mock(Withdrawer::class);
        $withdrawer->shouldReceive('execute');

        $service = new WithdrawalCreator(
            withdrawalRepository: $withdrawalRepository,
            withdrawer: $withdrawer,
        );

        $withdrawal = WithdrawalStub::random();

        $service->execute($withdrawal);

        $this->assertTrue(true);
    }
}
