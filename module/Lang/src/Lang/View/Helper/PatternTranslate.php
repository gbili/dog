<?php
namespace Lang\View\Helper;

class PatternTranslate extends \Zend\View\Helper\AbstractHelper
{
    public function __invoke($patterns, $replacements, $phrase, $textDomain = null)
    {
        if (null !== $textDomain) {
            throw new \Exception('Sorry, some changes were made to this helper signature, remove text domain; last param');
        }

        $view = $this->getView();

        $regexTranslatedPatterns = array_map(function ($pattern) use ($view){
            return '/' . $view->translate($pattern) . '/';
        }, $patterns);

        $translatedPhrase = $view->translate($phrase);

        return preg_replace($regexTranslatedPatterns, $replacements, $translatedPhrase);
    }
}
