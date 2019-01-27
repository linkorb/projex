<?php

namespace Projex\Exporter;

class ArrayExporter
{
    public function export(array $projects)
    {
        $data = [];
        foreach ($projects as $project) {
            $data[$project->getName()] = [
                'name' => $project->getName(),
                'group' => $project->getGroup(),
                'path' => $project->getPath(),
                'head' => $project->getHead(),
                'status' => $project->getStatus(),
            ];
        }
        return $data;
    }
}
