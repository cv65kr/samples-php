<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\Signal;

use Temporal\Workflow;
use Temporal\Samples\SimpleActivity\GreetingActivityInterface;
use Temporal\Activity\ActivityOptions;
use Carbon\CarbonInterval;
use Temporal\Samples\Subscription\AccountActivityInterface;

/**
 * Demonstrates asynchronous signalling of a workflow. Requires a local instance of Temporal server
 * to be running.
 */
class SignalWorkflow implements SignalWorkflowInterface
{
    private bool $exit = false;

    private $greetingActivity;

    private $account;

    public function __construct()
    {
        /**
         * Activity stub implements activity interface and proxies calls to it to Temporal activity
         * invocations. Because activities are reentrant, only a single stub can be used for multiple
         * activity invocations.
         */
        $this->greetingActivity = Workflow::newActivityStub(
            GreetingActivityInterface::class,
            ActivityOptions::new()->withStartToCloseTimeout(CarbonInterval::seconds(2))
        );

        $this->account = Workflow::newActivityStub(
            AccountActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(2))
        );
    }

    public function greet()
    {
        yield $this->greetingActivity->composeGreeting('Hello', 'world');
        yield Workflow::awaitWithTimeout(CarbonInterval::minutes(60), fn() => $this->exit);
        yield $this->account->sendWelcomeEmail('test');

    }

    public function addName(string $name): void
    {
    }

    public function exit(): void
    {
        $this->exit = true;
    }
}
