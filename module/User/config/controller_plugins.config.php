<?php
namespace User;
return array(
    'factories' => array(
        'nonce' => function ($controllerPluginManager) {
            $sm = $controllerPluginManager->getServiceLocator();
            $service = $sm->get('User\Service\Nonce');
            $plugin = new Controller\Plugin\Nonce;
            $plugin->setService($service);
            return $plugin;
        },
    ),
);
