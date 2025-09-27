<?php

namespace Test\Unit\Infrastructure\Repository\Db;

use App\Infrastructure\Repository\Db\DbAccountRepository;
use App\Domain\ValueObject\EntityId;
use App\Domain\Exception\AccountNotFoundException;
use App\Domain\Entity\Account;
use Hyperf\DbConnection\Db;
use PHPUnit\Framework\TestCase;
use DateTime;
use Mockery;

class DbAccountRepositoryTest extends TestCase
{
    public function testFindByIdReturnsAccount(): void
    {
        $id = '6437e406-e581-4208-9819-5510dba8ef79';
        $entityId = new EntityId($id);
        $row = (object) [
            'id' => $id,
            'name' => 'Test User',
            'balance' => '100.50',
            'created_at' => '2023-01-01 10:00:00',
            'updated_at' => '2023-01-02 11:00:00',
        ];

        $database = Mockery::mock(Db::class);
        $database->shouldReceive('table')->with('account')->andReturnSelf();
        $database->shouldReceive('where')->with('id', $id)->andReturnSelf();
        $database->shouldReceive('first')->andReturn($row);

        $repo = new DbAccountRepository($database);
        $account = $repo->findById($entityId);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($id, $account->id->value);
        $this->assertEquals('Test User', $account->name);
        $this->assertEquals(100.50, $account->balance());
        $this->assertEquals(new DateTime('2023-01-01 10:00:00'), $account->createdAt);
        $this->assertEquals(new DateTime('2023-01-02 11:00:00'), $account->updatedAt());
    }

    public function testFindByIdThrowsIfNotFound(): void
    {
        $id = '6437e406-e581-4208-9819-5510dba8ef79';
        $entityId = new EntityId($id);

        $database = Mockery::mock(Db::class);
        $database->shouldReceive('table')->with('account')->andReturnSelf();
        $database->shouldReceive('where')->with('id', $id)->andReturnSelf();
        $database->shouldReceive('first')->andReturn(null);

        $repo = new DbAccountRepository($database);
        $this->expectException(AccountNotFoundException::class);
        $repo->findById($entityId);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
