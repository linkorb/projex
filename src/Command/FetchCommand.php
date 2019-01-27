<?php

namespace Projex\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Projex\Service\ProjectService;
use Symfony\Component\Process\Process;
use RuntimeException;

class FetchCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('fetch')
            ->setDescription('Fetches origin master')
            ->addOption(
                'pull-if-master',
                null,
                InputOption::VALUE_NONE,
                'Direct pull if the repo is on master'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $pullIfMaster = $input->getOption('pull-if-master');
        $projectService = ProjectService::fromEnv();
        $projects = $projectService->load();
        
        foreach ($projects as $project) {
            $output->writeln('<info>Starting '.$project->getName().' ... </info>');

            $process = new Process(['git', 'fetch', 'origin']);
            $process->setTimeout(60 * 10);
            $process->setWorkingDirectory($project->getPath());
            $process->run();
            $output->writeLn($process->getOutput());
            $output->writeLn($process->getErrorOutput());
            
        }
        $output->writeln('<info>'.count($projects)." projects fetched.</info>");
    }
}
