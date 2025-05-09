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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowOptions;
use Temporal\SampleUtils\Command;
use Temporal\Testing\Replay\WorkflowReplayer;

class ReplayCommand extends Command
{
    protected const NAME = 'replay-activity-retry';
    protected const DESCRIPTION = 'Execute ActivityRetry\GreetingWorkflow';

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        (new WorkflowReplayer())->replayFromJSON(
            workflowType: 'ActivityRetry.greet',
            path: __DIR__ . '/test.json',
        );

        return self::SUCCESS;
    }
}