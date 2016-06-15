<?php

namespace Projex\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Projex\Scanner;
use Projex\Exporter\AtomProjectsCsonExporter;
use RuntimeException;

class GitFetchCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('git:fetch')
            ->setDescription('Scans projects and fetch origin master')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The path to scan'
            )
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
        $path = $input->getOption('path');
        $pullIfMaster = $input->getOption('pull-if-master');
        if ($path) {
            $path = rtrim($path, '/');
            if (strpos($path, '~/') === 0) {
                $path = getenv("HOME").substr($path, 1);
            }
        } else {
            $path = getenv("HOME").'/git';
        }
        $output->write("Projex: Scanning projects (path = $path)\n");
        $scanner = new Scanner();
        $projects = $scanner->scan($path);

        foreach ($projects as $project) {
            $done = false;
            $output->writeln('<info>Starting '.$project->getPath().' ... </info>');
            if ($pullIfMaster) {
                if (trim(shell_exec('cd '.$project->getPath().' && git rev-parse --abbrev-ref HEAD')) == 'master') {
                    $output->writeln('<comment>Master branch - direct pulling ... '.shell_exec('cd '.$project->getPath().' && git pull').' done!</comment>');
                    $done = true;
                }
            }

            if (!$done) {
                $output->writeln('<comment>Fetching origin ... '.shell_exec('cd '.$project->getPath().' && git fetch origin').' done!</comment>');
            }
        }
        $output->writeln('<info>'.count($projects)." projects fetched.</info>");
    }
}
