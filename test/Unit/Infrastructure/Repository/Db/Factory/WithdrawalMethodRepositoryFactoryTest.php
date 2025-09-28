<?php

namespace Test\Unit\Infrastructure\Repository\Db\Factory;

use App\Domain\Enum\WithdrawalMethodType;
use App\Infrastructure\Repository\Db\DbPixRepository;
use App\Infrastructure\Repository\Db\Factory\WithdrawalMethodRepositoryFactory;
use PHPUnit\Framework\TestCase;

class WithdrawalMethodRepositoryFactoryTest extends TestCase
{
    public function testMakeReturnsPixRepositoryForPixMethodType(): void
    {
        $factory = new WithdrawalMethodRepositoryFactory();

        $result = $factory->make(WithdrawalMethodType::PIX);

        $this->assertInstanceOf(DbPixRepository::class, $result);
    }
}
