<?php
namespace Lang;
return array(
    'invokables' => array(
        'patternTranslate' => __NAMESPACE__ . '\View\Helper\PatternTranslate',
    ),

    'factories' => array(
        'langSelector' => __NAMESPACE__ . '\View\Helper\LangSelectorFactory',

        'translate'        => function ($vhp) {
            $sm = $vhp->getServiceLocator();
            $translate = new View\Helper\TranslateWriter;
            $translate->setTextDomainService($sm->get('textdomain'));
            return $translate;
        },

        'lang' => function ($viewHelperPluginManager) {
            $currentLang = $viewHelperPluginManager->getServiceLocator()
                ->get('lang')->getLang();
            $langHelper = new View\Helper\Lang();
            $langHelper->setLang($currentLang);
            return $langHelper;
        },
        'dateTimeFormat' => function ($viewHelperPluginManager) {
            $service = $viewHelperPluginManager->getServiceLocator()
                ->get('lang');
            $helper = new View\Helper\DateTimeFormat();
            $helper->setService($service);
            return $helper;
        },
    ),
);
