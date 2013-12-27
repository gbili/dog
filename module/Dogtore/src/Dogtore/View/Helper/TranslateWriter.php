<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Dogtore\View\Helper;

/**
 * View helper for translating messages.
 */
class TranslateWriter extends \Zend\I18n\View\Helper\AbstractTranslatorHelper
{
    /**
     * Translate a message
     *
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke($message, $textDomain = null, $locale = null)
    {
        $translator = $this->getTranslator();
        if (null === $translator) {
            throw new \Zend\I18n\Exception\RuntimeException('Translator has not been set');
        }
        if (null === $textDomain) {
            $textDomain = $this->getTranslatorTextDomain();
        }
        if (null === $locale) {
            $locale = $this->getTranslator()->getLocale();
        }
        if ($textDomain === 'default') {
            throw new \Exception('Default text domain will result in a bad directory being used. grep this string and change the text domain in that call to translate:' . $message);
        }
        $this->addString($message, $textDomain, $locale);
        return $translator->translate($message, $textDomain, $locale);
    }

    public function addString($string, $lcModuleName, $locale)
    {
        $this->addStringToFile($string, $this->getFilename($lcModuleName, $locale));
    }

    public function getFilename($lcModuleName, $locale)
    {
        $dir = realpath(__DIR__ . '/../../../../../' . ucfirst($lcModuleName) . '/language');
        if (!is_dir($dir)) {
            throw new \Exception('The directory does not exist. Param $lcModuleName: ' . $lcModuleName);
        }
        return $filename = $dir . '/' . $locale . '.php';
    }
    
    public function getFileContents($filename)
    {
        if (!file_exists($filename)) {
            return array();
        }
        return include $filename;
    }

    public function addStringToFile($string, $filename)
    {
        $strings = $this->getFileContents($filename);
        if (isset($strings[$string])) {
            return;
        }
        $strings[$string] = '';

        if (!is_writable(dirname($filename))) {
            throw new \Exception('Cannot write in : ' . $filename);
        }

        $contents = "<?php\nreturn " . var_export($strings, true) . ";";
        return file_put_contents($filename, $contents);
    }
}
