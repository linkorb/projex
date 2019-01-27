<?php

namespace Projex;

class Project
{
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    private $group;
    
    public function getGroup()
    {
        return $this->group;
    }
    
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }
    
    private $path;
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    private $head;
    
    public function getHead()
    {
        return $this->head;
    }
    
    public function setHead($head)
    {
        $this->head = $head;
        return $this;
    }

    private $status;
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function isDirty()
    {
        if (trim($this->status)=='') {
            return false;
        }
        return true;
    }
}
