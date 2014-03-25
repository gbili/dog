<?php
namespace Scs;
return array(
    'invokables' => array(
        'postButtons' => __NAMESPACE__ . '\View\Helper\PostButtonsGenerator',
        'postLabels'  => __NAMESPACE__ . '\View\Helper\PostLabelsGenerator',
    ),
    'factories' => array(
        'scs'  => function ($vhp) {
            $sm = $vhp->getServiceLocator();
            $helper = new View\Helper\Scs;
            $helper->setService($sm->get('scs'));
            return $helper;
        },
    ),
);
