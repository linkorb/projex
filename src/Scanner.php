<?php

namespace Projex;

use Projex\Project;

class Scanner
{
    public function scan($path)
    {
        $dirs = array();
        $this->scanRecursive($path, $dirs);
        
        $projects= array();
        foreach ($dirs as $dir) {
            $project = new Project();
            $project->setName(basename(dirname($dir)) . '/' . basename($dir));
            $project->setGroup(basename(dirname($dir)));
            
            $project->setPath($dir);
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
