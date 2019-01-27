<?php

namespace Projex\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Projex\Scanner;
use Projex\Exporter\AtomProjectManagerCsonExporter;
use RuntimeException;

class AtomUpdateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('atom:update')
            ->setDescription('Updates your ~/.atom/projects.cson file based on scanner output')
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
        
        // Update projects.cson (for project-manager plugin)
        $exporter = new AtomProjectManagerCsonExporter();
        $cson = $exporter->export($projects);
        file_put_contents($home . '/.atom/projects.cson', $cson);

        // Update project-viewer.json
        $filename = $home . "/.atom/storage/project-viewer.json";
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        $seen = [];

        foreach ($data['projects'] as $pd) {
            $seen[$pd['name']] = true;
        }

        foreach ($data['groups'] as $gd) {
            echo $gd['name'];
            foreach ($gd['projects'] as $pd) {
                $seen[$pd['name']] = true;
            }
        }
        
        foreach ($projects as $project) {
            if (!isset($seen[$project->getName()])) {
                //echo "NEW: " . $project->getName() . "\n";
                $pdata = [
                    'name' => $project->getName(),
                    'icon' => '',
                    'dev' => false,
                    'paths' => [
                        $project->getPath()
                    ]
                ];
                $data['projects'][] = $pdata;
            } else {
                //echo "EXISTING: " . $project->getName() . "\n";
            }
        }
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo $json;
        file_put_contents($filename, $json);
        
        $output->write("Added " . count($projects) . " projects\n");
    }
}
