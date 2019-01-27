<?php

namespace Projex\Service;

use Projex\Project;
use Symfony\Component\Process\Process;
use RuntimeException;

class ProjectService
{
    protected $gitPath;
    protected $dataPath;

    private function __construct()
    {
    }

    public static function fromEnv()
    {
        $service = new ProjectService();
        $service->gitPath = getenv('PROJEX_GIT_PATH');
        $service->dataPath = getenv('PROJEX_DATA_PATH');
        return $service;
    }
    public function load()
    {
        if (!file_exists($this->dataPath . '/projects.json')) {
            throw new RuntimeException("projects.json not found, please run `project:scan` to generate it");
        }
        $json = file_get_contents($this->dataPath . '/projects.json');
        $rows = json_decode($json, true);
        $projects = [];
        foreach ($rows as $row) {
            $project = new Project();
            $project->setName($row['name']);
            $project->setGroup($row['group']);
            $project->setPath($row['path']);
            $project->setStatus($row['status']);
            $projects[] = $project;
        }
        return $projects;
    }

    public function scan()
    {
        $dirs = array();
        $this->scanRecursive($this->gitPath, $dirs);
        
        $projects= array();
        foreach ($dirs as $dir) {
            $project = new Project();
            $project->setName(basename(dirname($dir)) . '/' . basename($dir));
            $project->setGroup(basename(dirname($dir)));
            $head = trim(file_get_contents($dir . '/.git/HEAD'));


            $project->setHead($head);
            
            $project->setPath($dir);

            $process = new Process(['git', 'status', '--porcelain']);
            $process->setWorkingDirectory($dir);
            $process->run();
            $project->setStatus($process->getOutput());

            $projects[] = $project;
        }
        return $projects;
    }
    
    private function scanRecursive($path, &$dirs)
    {
        $files = scandir($path);
        foreach ($files as $filename) {
            $skip = false;
            switch ($filename) {
                case '.':
                case '..':
                case '.git':
                case 'vendor':
                case 'node_modules':
                    $skip = true;
                    break;
            }
            
            if (!$skip) {
                if (is_dir($path . '/' . $filename)) {
                    if (file_exists($path . '/' . $filename . '/.git/HEAD')) {
                        // Found a .git repository, add it to the dirs list
                        $dirs[] = $path . '/' . $filename;
                    } else {
                        $this->scanRecursive($path . '/' . $filename, $dirs);
                    }
                }
            }
        }
    }
}
