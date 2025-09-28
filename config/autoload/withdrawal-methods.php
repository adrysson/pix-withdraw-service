<?php

use App\Application\CreateWithdrawal\Factory\PixFactory;
use App\Domain\Enum\PixKeyType;
use App\Domain\Enum\WithdrawalMethodType;
use App\Infrastructure\Repository\Db\DbPixRepository;

return [
    WithdrawalMethodType::PIX->value => [
        'validation' => [
            'pix' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'array',
            ],
            'pix.type' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'string',
                'in:' . implode(',', array_map(fn($e) => $e->value, PixKeyType::cases())),
            ],
            'pix.key' => [
                'required_if:method,' . WithdrawalMethodType::PIX->value,
                'string',
                ],
        ],
        'factory' => PixFactory::class,
        'persistence' => [
            'repository' => DbPixRepository::class,
        ]
    ],
];
