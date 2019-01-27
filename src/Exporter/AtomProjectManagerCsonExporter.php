<?php

namespace Projex\Exporter;

class AtomProjectManagerCsonExporter
{
    public function export($projects)
    {
        $cson = '';
        foreach ($projects as $project) {
            $cson .= '\'' . $project->getName() . '\':' . PHP_EOL;
            $cson .= '  \'title\': \'' . $project->getName() . '\'' . PHP_EOL;
            $cson .= '  \'icon\': \'icon-repo\'' . PHP_EOL;
            $cson .= '  \'group\': \'' . $project->getGroup() . '\'' . PHP_EOL;
            $cson .= '  \'paths\': [' . PHP_EOL;
            $cson .= '     \'' . $project->getPath() . '\'' . PHP_EOL;
            $cson .= '  ]' . PHP_EOL;
            $cson .= PHP_EOL;
        }
        return $cson;
    }
}
