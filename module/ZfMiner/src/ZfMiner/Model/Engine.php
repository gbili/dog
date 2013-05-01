<?php
namespace ZfMiner\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;

abstract class ManagerAbstract
extends sadf
implements AdapterAwareInterface 
{
    /**
     * @var Zend\Db\Adapter\Adapter
     */
    protected $adapter;

    // some code 

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    // some more code
}