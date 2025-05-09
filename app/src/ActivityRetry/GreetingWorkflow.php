<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\ActivityRetry;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Exception\IllegalStateException;
use Temporal\Workflow;

/**
 * Demonstrates activity retries using an exponential backoff algorithm. Requires a local instance
 * of the Temporal service to be running.
 */
class GreetingWorkflow implements GreetingWorkflowInterface
{
    private $greetingActivity;

    public function __construct()
    {
        /**
         * To enable activity retry set {@link RetryOptions} on {@link ActivityOptions}.
         */
        $this->greetingActivity = Workflow::newActivityStub(
            GreetingActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(10))
                ->withRetryOptions(
                    RetryOptions::new()
                        ->withInitialInterval(CarbonInterval::seconds(1))
                        ->withMaximumAttempts(5)
                        ->withNonRetryableExceptions([\InvalidArgumentException::class])
                )
        );
    }

    public function greet(string $name): \Generator
    {
        $version = Workflow::getVersion('test', -1, 1);
        if ((int) $version === -1) {
            return yield $this->greetingActivity->composeGreeting('Hello', $name);
        } else {
            yield Workflow::sideEffect(fn() => 'test');
            return '1';
        }
    }
}