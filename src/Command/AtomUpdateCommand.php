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

class AtomUpdateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('atom:update')
            ->setDescription('Updates your ~/.atom/projects.cson file')
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
        $home = getenv("HOME");
        $path = $input->getOption('path');
        if ($path) {
            if (strpos($path, '~/') === 0) {
                $path = $home.substr($path, 1);
            }
        } else {
            $path = $home.'/git';
        }
        $output->write("Projex: Generating ~/.atom/projects.cson (path = $path)\n");
        $scanner = new Scanner();
        $projects = $scanner->scan($path);
        $exporter = new AtomProjectsCsonExporter();
        $cson = $exporter->export($projects);
        file_put_contents($home . '/.atom/projects.cson', $cson);
        $output->write("Added " . count($projects) . " projects\n");
    }
}
