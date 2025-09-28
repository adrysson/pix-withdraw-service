<?php

namespace App\Presentation\Controller;

use App\Application\CreateWithdrawal\CreateWithdrawCommand;
use App\Application\CreateWithdrawal\CreateWithdrawHandler;
use App\Presentation\Request\AccountWithdrawRequest;
use App\Presentation\Resource\WithdrawResource;
use Hyperf\HttpServer\Annotation\Controller;
use Psr\Http\Message\ResponseInterface;

/**
 * @Controller(prefix="/account")
 */
class AccountWithdrawController extends AbstractController
{
    public function __construct(
        protected CreateWithdrawHandler $createWithdrawHandler,
    ) {  
    }

    public function withdraw(AccountWithdrawRequest $request): ResponseInterface
    {
        $command = new CreateWithdrawCommand(
            accountId: $request->accountId(),
            amount: $request->amount(),
            methodType: $request->methodType(),
            methodData: $request->methodData(),
            schedule: $request->schedule(),
        );

        $withdrawal = $this->createWithdrawHandler->handle($command);

        $resource = new WithdrawResource($withdrawal);

        return $this->response->json($resource);
    }
}
