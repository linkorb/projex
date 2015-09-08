<?php

namespace Projex\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $home = getenv("HOME");
        $output->write("Projex: Generating ~/.atom/projects.cson (home = $home)\n");
        $scanner = new Scanner();
        $projects = $scanner->scan($home . '/git');
        $exporter = new AtomProjectsCsonExporter();
        $cson = $exporter->export($projects);
        file_put_contents($home . '/.atom/projects.cson', $cson);
        $output->write("Added " . count($projects) . " projects\n");
    }
}
