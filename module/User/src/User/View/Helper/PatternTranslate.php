<?php
namespace User\View\Helper;

class PatternTranslate extends \Zend\View\Helper\AbstractHelper
{
    public function __invoke($patterns, $replacements, $phrase, $textDomain)
    {
        $view = $this->getView();

        $regexTranslatedPatterns = array_map(function ($pattern) use ($view, $textDomain){
            return '/' . $view->translate($pattern, $textDomain) . '/';
        }, $patterns);

        $translatedPhrase = $view->translate($phrase, $textDomain);
        return preg_replace($regexTranslatedPatterns, $replacements, $translatedPhrase);
    }
}
