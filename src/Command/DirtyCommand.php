<?php

namespace Projex\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Projex\Service\ProjectService;
use Projex\Exporter\ArrayExporter;
use RuntimeException;

class DirtyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('dirty')
            ->setDescription('Lists dirty projects (i.e. `git status` is not empty)')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $projectService = ProjectService::fromEnv();
        $projects = $projectService->load();
        foreach ($projects as $project) {
            if ($project->isDirty()) {
                $output->writeLn($project->getName());
                // $output->writeLn($project->getStatus());
            }
        }
    }
}
