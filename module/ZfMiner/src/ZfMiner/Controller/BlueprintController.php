<?php
namespace ZfMiner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlueprintController 
extends AbstractActionController
{
    
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * Create the tables needed to save
     * the zfminer data
     * This code should be run only once or produce an error
     * then be given an option to update (erase existing)
     * 
     */
    public function installAction()
    {
        return new ViewModel();
    }

    public function uninstallAction()
    {
        return new ViewModel();
    }
    
    public function status()
    {
        return new ViewModel();
    }

    public function updateAction()
    {
        return new ViewModel();
    }
}