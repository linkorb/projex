<?php

namespace Projex\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $home = getenv("HOME");
        $output->write("Projex: Scanning projects (home = $home)\n");
        $scanner = new Scanner();
        $projects = $scanner->scan($home . '/git');
        
        foreach ($projects as $project) {
            $output->write(" * " . $project->getPath() . "\n");
        }
        $output->write("Found " . count($projects) . " projects\n");
    }
}
