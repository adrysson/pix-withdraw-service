<?php

namespace Test\Unit\Infrastructure\Repository\Db;

use App\Domain\Collection\WithdrawalCollection;
use App\Infrastructure\Repository\Db\DbWithdrawalRepository;
use App\Domain\Entity\Account;
use App\Infrastructure\Repository\Db\DbAccountRepository;
use App\Domain\Entity\Pix;
use App\Infrastructure\Repository\Db\DbPixRepository;
use App\Infrastructure\Repository\Db\Mapper\PixMapper;
use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use Hyperf\DbConnection\Db;
use PHPUnit\Framework\TestCase;
use DateTime;
use Mockery;
use Test\Stubs\Domain\Entity\PixStub;

class DbWithdrawalRepositoryTest extends TestCase
{
    public function testcreateWithdrawalInsertsWithdrawalAndPix(): void
    {
        $withdrawal = $this->makeWithdrawal();

        $database = Mockery::mock(Db::class);
        $database->shouldReceive('beginTransaction')->once();
        $database->shouldReceive('table')->with('account_withdraw')->andReturnSelf();
        $database->shouldReceive('insert')->once();
        $database->shouldReceive('table')->with('account_withdraw_pix')->andReturnSelf();
        $database->shouldReceive('insert')->once();
        $database->shouldReceive('commit')->once();


        $accountRepository = Mockery::mock(DbAccountRepository::class);

        $pixRepository = Mockery::mock(DbPixRepository::class);
        $pixRepository->shouldReceive('insert')->once();

        $repo = new DbWithdrawalRepository($database, $accountRepository, $pixRepository);

        $repo->create($withdrawal);
        $this->assertFalse($withdrawal->done());
    }

    public function testWithdrawUpdatesAccountAndFinishesWithdrawal(): void
    {
        $accountId = new AccountId('6437e406-e581-4208-9819-5510dba8ef79');
        $withdrawal = new Withdrawal(
            new WithdrawalId('b7e6a1c2-1d2e-4f3a-9b4c-2a1b3c4d5e6f'),
            $accountId,
            PixStub::random(),
            10.0,
            null,
            false,
            new DateTime('2023-01-01 10:00:00'),
            new DateTime('2023-01-01 10:00:00')
        );

        $account = new Account(
            $accountId,
            'Test User',
            90.0,
            new \DateTime('2023-01-01 10:00:00'),
            new \DateTime('2023-01-01 10:00:00')
        );


        $database = Mockery::mock(Db::class);
        $database->shouldReceive('beginTransaction')->once();
        $database->shouldReceive('commit')->once();
        $database->shouldReceive('table')->with('account_withdraw')->andReturnSelf();
        $database->shouldReceive('where')->with('id', $withdrawal->id->value)->andReturnSelf();
        $database->shouldReceive('update')->once();

        $accountRepository = Mockery::mock(DbAccountRepository::class);
        $accountRepository->shouldReceive('findById')->with($database, $accountId, true)->andReturn($account);
        $accountRepository->shouldReceive('update')->once();

        $pixRepository = Mockery::mock(DbPixRepository::class);

        $repo = new DbWithdrawalRepository($database, $accountRepository, $pixRepository);

        $repo->withdraw($withdrawal);

        $this->assertTrue($withdrawal->done());
    }

    public function testFinishWithdrawalUpdatesWithdrawal(): void
    {
        $withdrawal = new Withdrawal(
            new WithdrawalId('b7e6a1c2-1d2e-4f3a-9b4c-2a1b3c4d5e6f'),
            new AccountId('6437e406-e581-4208-9819-5510dba8ef79'),
            PixStub::random(),
            100.0,
            null,
            true,
            new DateTime('2023-01-01 10:00:00'),
            new DateTime('2023-01-01 10:00:00')
        );

        $database = Mockery::mock(Db::class);
        $database->shouldReceive('table')->with('account_withdraw')->andReturnSelf();
        $database->shouldReceive('where')->with('id', $withdrawal->id->value)->andReturnSelf();
        $database->shouldReceive('update')->once();

        $accountRepository = Mockery::mock(DbAccountRepository::class);

        $pixRepository = Mockery::mock(DbPixRepository::class);

        $repo = new DbWithdrawalRepository($database, $accountRepository, $pixRepository);

        $repo->finish($withdrawal, null);
        $this->assertTrue($withdrawal->done());
    }

    private function makeWithdrawal(): Withdrawal
    {
        return new Withdrawal(
            new WithdrawalId('c1d2e3f4-5678-1234-9abc-def012345678'),
            new AccountId('c1d2e3f4-5678-1234-9abc-def012345678'),
            PixStub::random(),
            100.0,
            null,
            false,
            new \DateTime('2023-01-01 10:00:00'),
            new \DateTime('2023-01-01 10:00:00')
        );
    }

    public function testFindPendingWithdrawalsReturnsCollection(): void
    {
        $now = new DateTime();
        $past = (clone $now)->modify('-1 day')->format('Y-m-d H:i:s');
        $rows = [
            (object) [
                'id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
                'account_id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
                'method' => 'pix',
                'amount' => 100.0,
                'scheduled' => true,
                'scheduled_for' => $past,
                'done' => false,
                'created_at' => $past,
                'updated_at' => $past,
            ],
        ];

        $database = Mockery::mock(Db::class);
        $database->shouldReceive('table')->with('account_withdraw')->andReturnSelf();
        $database->shouldReceive('where')->with('done', false)->andReturnSelf();
        $database->shouldReceive('where')->with('scheduled', true)->andReturnSelf();
        $database->shouldReceive('whereNotNull')->with('scheduled_for')->andReturnSelf();
        $database->shouldReceive('where')->with('scheduled_for', '<', Mockery::type('string'))->andReturnSelf();
        $database->shouldReceive('get')->andReturn($rows);

        $pixRow = (object) [
            'id' => 'c1d2e3f4-5678-1234-9abc-def012345678',
            'type' => 'email',
            'key' => 'user@example.com',
            'created_at' => $past,
            'updated_at' => $past,
        ];
        $database->shouldReceive('table')->with('account_withdraw_pix')->andReturnSelf();
        $database->shouldReceive('where')->with('account_withdraw_id', 'c1d2e3f4-5678-1234-9abc-def012345678')->andReturnSelf();
        $database->shouldReceive('first')->andReturn($pixRow);

        $accountRepository = Mockery::mock(DbAccountRepository::class);

        $pixRepository = Mockery::mock(DbPixRepository::class);
        $pixRepository->shouldReceive('findByWithdrawalId')->andReturnUsing(function($withdrawalId) use ($pixRow) {
            return PixMapper::mapPix($pixRow);
        });

        $repo = new DbWithdrawalRepository($database, $accountRepository, $pixRepository);
        $result = $repo->findPending();

        $this->assertInstanceOf(WithdrawalCollection::class, $result);
        $this->assertCount(1, $result->all());
        $withdrawal = $result->all()[0];
        $this->assertInstanceOf(Withdrawal::class, $withdrawal);
        $this->assertEquals('c1d2e3f4-5678-1234-9abc-def012345678', $withdrawal->id->value);
        $this->assertEquals('c1d2e3f4-5678-1234-9abc-def012345678', $withdrawal->accountId->value);
        $this->assertEquals(100.0, $withdrawal->amount);
        $this->assertFalse($withdrawal->done());
        $this->assertInstanceOf(Pix::class, $withdrawal->method);
        /** @var Pix $method */
        $method = $withdrawal->method;
        $this->assertEquals('user@example.com', $method->key->value);
    }
}
