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

class ScanCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('projects:scan')
            ->setDescription('Scans your PROJEX_GIT_PATH for projects and caches the results in projects.json')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $projectService = ProjectService::fromEnv();
        $projects = $projectService->scan();

        $exporter = new ArrayExporter();
        $data = $exporter->export($projects);
        $json = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        file_put_contents(getenv('PROJEX_DATA_PATH') . '/projects.json', $json);

        $output->writeLn($json);
    }
}
