<?php

namespace Test\Unit\Infrastructure\Repository\Db\Factory;

use App\Domain\Enum\WithdrawalMethodType;
use App\Infrastructure\Repository\Db\DbPixRepository;
use App\Infrastructure\Repository\Db\Factory\WithdrawalMethodRepositoryFactory;
use Hyperf\DbConnection\Db;
use Mockery;
use PHPUnit\Framework\TestCase;

class WithdrawalMethodRepositoryFactoryTest extends TestCase
{
    public function testMakeReturnsPixRepositoryForPixMethodType(): void
    {
        $factory = new WithdrawalMethodRepositoryFactory();

        $database = Mockery::mock(Db::class);

        $result = $factory->make(
            database: $database,
            methodType: WithdrawalMethodType::PIX,
        );

        $this->assertInstanceOf(DbPixRepository::class, $result);
    }
}
