<?php
namespace ZfMiner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Gbili\Miner\Installer;
use Gbili\Db\Req\Admin;

class EngineController extends AbstractActionController
{
    
    /**
     * (non-PHPdoc)
     * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
     */
    public function indexAction()
    {
        $vars = array();
        $req = new Admin(__NAMESPACE__);
        if (false === $schema = $req->describeDatabase()) {
            $vars['error'] = 'There are no tables';
        } else {
            $vars['schema'] = $schema;
        }
        return new ViewModel($vars);
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
        $vars = array();
        $installer = new Installer(__NAMESPACE__);
        if (!$installer->install()) {
            $vars['error'] = $installer->getError();
        } else {
            $vars['msg'] = 'Successfully installed.';
        }
        return new ViewModel($vars);
    }

    /**
     * 
     */
    public function uninstallAction()
    {
        $vars = array();
        $installer = new Installer(__NAMESPACE__);
        if (!$installer->uninstall()) {
            $vars['error'] = $installer->getError();
        } else {
            $vars['msg'] = 'Successfully uninstalled.';
        }
        return new ViewModel($vars);
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