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

class ProjectsScanCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('projects:scan')
            ->setDescription('Scans your computer for projects')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The path to scan'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getOption('path');
        if ($path) {
            if (strpos($path, '~/') === 0) {
                $path = getenv("HOME").substr($path, 1);
            }
        } else {
            $path = getenv("HOME").'/git';
        }
        $output->writeln("<comment>Projex: Scanning projects (path = <info>$path</info>)</comment>");
        $scanner = new Scanner();
        $projects = $scanner->scan($path);

        foreach ($projects as $project) {
            $output->writeln('<info> * </info>'.$project->getPath());
        }
        $output->writeln('Found <info>'.count($projects).'</info> projects.');
    }
}
