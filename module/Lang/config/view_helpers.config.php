<?php
namespace Lang;
return array(
    'initializers' => array(
        'injectTranslatorTextDomain' => function ($helper, $vhp) {
            if ($helper instanceof \Zend\I18n\Translator\TranslatorAwareInterface) {
                $helper->setTranslatorTextDomain($vhp->getServiceLocator()->get('textdomain')->getTextdomain());
            }
        }
    ),

    'invokables' => array(
        'patternTranslate' => __NAMESPACE__ . '\View\Helper\PatternTranslate',
    ),

    'factories' => array(
        'langSelector' => __NAMESPACE__ . '\View\Helper\LangSelectorFactory',

        //translateWriter
        'translate'        => function ($vhp) {
            $sm = $vhp->getServiceLocator();
            $translate = new View\Helper\TranslateWriter;
            $translate->setTranslationStorageService($sm->get('translationStorage'));
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
