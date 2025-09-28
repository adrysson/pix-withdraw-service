<?php

namespace Test\Unit\Infrastructure\Repository\Db\Factory;

use App\Domain\Enum\WithdrawalMethodType;
use App\Infrastructure\Repository\Db\DbPixRepository;
use App\Infrastructure\Repository\Db\Factory\WithdrawalMethodRepositoryFactory;
use PHPUnit\Framework\TestCase;
use Mockery;

class WithdrawalMethodRepositoryFactoryTest extends TestCase
{
    public function testMakeReturnsPixRepositoryForPixMethodType(): void
    {
        $pixRepository = Mockery::mock(DbPixRepository::class);
        $factory = new WithdrawalMethodRepositoryFactory($pixRepository);

        $result = $factory->make(WithdrawalMethodType::PIX);

        $this->assertEquals($pixRepository, $result);
    }
}
