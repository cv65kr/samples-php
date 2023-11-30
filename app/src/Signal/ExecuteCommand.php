<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\Signal;

use Carbon\CarbonInterval;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowOptions;
use Temporal\SampleUtils\Command;

class ExecuteCommand extends Command
{
    protected const NAME = 'signal';
    protected const DESCRIPTION = 'Execute Signal\SignalWorkflow with multiple signals';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $workflow = $this->workflowClient->newWorkflowStub(
            SignalWorkflowInterface::class,
            WorkflowOptions::new()
        );

        $output->writeln("Starting <comment>SignalWorkflow</comment>... ");

        $run = $this->workflowClient->start($workflow);

        $output->writeln(
            sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>, RunID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
                $run->getExecution()->getRunID(),
            )
        );

        if ((bool) random_int(0,1)) {
            $workflow->exit();
        }

        return self::SUCCESS;
    }
}
