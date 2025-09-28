<?php

namespace App\Presentation\Controller;

use App\Application\CreateWithdrawal\CreateWithdrawCommand;
use App\Application\CreateWithdrawal\CreateWithdrawHandler;
use App\Presentation\Request\AccountWithdrawRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HyperfResponseInterface;
use Throwable;

/**
 * @Controller(prefix="/account")
 */
class AccountWithdrawController
{
    public function __construct(
        protected CreateWithdrawHandler $createWithdrawHandler,
    ) {  
    }

    public function withdraw(AccountWithdrawRequest $request, HyperfResponseInterface $response): ResponseInterface
    {
        $command = new CreateWithdrawCommand(
            accountId: $request->accountId(),
            amount: $request->amount(),
            methodType: $request->methodType(),
            methodData: $request->methodData(),
            schedule: $request->schedule(),
        );

        try {
            $this->createWithdrawHandler->handle($command);
        } catch (Throwable $throwable) {
            return $response->json(['error' => $throwable->getMessage()]);
        }

        return $response->json(['success' => true]);
    }
}
