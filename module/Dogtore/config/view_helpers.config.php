<?php
namespace Dogtore;
return array(
    'invokables' => array(
        'userHasNDogsAndTheirNamesArePhrase' => __NAMESPACE__ . '\View\Helper\UserHasNDogsAndTheirNamesAre', 
        'colorDecreaser' => __NAMESPACE__ . '\View\Helper\DogColor', 
    ),
    'factories' => array(
        'colors' => function ($viewHelperPluginManager) {
            $sm = $viewHelperPluginManager->getServiceLocator();
            $helper = new \GbiliViewHelper\View\Helper\Colors;
            $helper->setColorFilter(new \Gbili\Color\ColorFilter);
            return $helper;
        },
    ),
);
