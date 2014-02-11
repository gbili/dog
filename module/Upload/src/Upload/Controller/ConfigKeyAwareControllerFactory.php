<?php
namespace Upload\Controller;

class ConfigKeyAwareControllerFactory
{
    public function __invoke(\Zend\ServiceManager\ServiceLocatorInterface $sm, $cName, $rName) {
        //\ModuleName\Controller\ControllerNameController
        if ((false !== strpos($rName, '\\')) && class_exists($rName)) {
            $className = $rName;
        //modulename_controllername_controller
        } else if (false !== strpos($rName, '_controller')) {
            $parts = explode('_', $rName);
            $className = '\\' . ucfirst($parts[0]) . '\Controller\\' . ucfirst($parts[1]) . 'Controller';
        }

        $controller = new $className;
        if ($controller instanceof \Upload\ConfigKeyAwareInterface) {
            $controller->setConfigKey($rName);
        }
        return $controller;
    }
}
