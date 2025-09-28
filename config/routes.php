<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Presentation\Controller\AccountWithdrawController;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Presentation\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::post('/account/{accountId}/balance/withdraw', [AccountWithdrawController::class, 'withdraw']);
