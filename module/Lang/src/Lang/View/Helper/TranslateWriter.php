<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Lang\View\Helper;

/**
 * View helper for translating messages.
 */
class TranslateWriter extends \Zend\I18n\View\Helper\AbstractTranslatorHelper
{
    protected $translationStorageService;

    /**
     * Translate a message
     *
     * @param  string $message
     * @param  string $textdomain
     * @param  string $locale
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke($message, $textdomain = null, $locale = null)
    {
        $translator = $this->getTranslator();
        if (null === $translator) {
            throw new \Zend\I18n\Exception\RuntimeException('Translator has not been set');
        }
        if (null === $textdomain) {
            $textdomain = $this->getTranslatorTextdomain();
        }

        if (null === $locale) {
            $locale = $this->getTranslator()->getLocale();
        }
        if ($textdomain === 'default') {
            throw new \Exception('Default text domain will result in a bad directory being used. grep this string and change the text domain in that call to translate:' . $message);
        }
        $this->translationStorageService->setTranslation($textdomain, $locale, $message, $translation='', $overwrite=false);

        return $translator->translate($message, $textdomain, $locale);
    }

    /**
     * Every string passed to __invoke is passed to the storage service
     * @return \Lang\Service\TranslationStorage
     */
    public function getTranslationStorageService()
    {
        return $this->translationStorageService;
    }

    public function setTranslationStorageService($service)
    {
        $this->translationStorageService = $service;
        return $this;
    }
}
