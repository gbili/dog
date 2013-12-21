<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\View\Helper;

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
        if (null !== $locale) {
            $locale = $this->getTranslator();
            $this->addStringToFile($message, $textDomain, $locale);
        }
        return $translator->translate($message, $textDomain, $locale);
    }

    public function addStringToFile($string, $lcModuleName, $locale)
    {
        $filename = $this->getFilename($lcModuleName, $locale);
        if ($this->isStringInFile($string, $filename)) {
            return true;
        }
        $contents = 'msgid "'. $string . '"' . "\n";
        $contents .= 'msgstr ""' . "\n";
        $contents .= "\n";
        return $this->fileAppend($filename, $contents);
    }

    public function getFilename($lcModuleName, $locale)
    {
        $dir = realpath(__DIR__ . '/../../../../../' . ucfirst($lcModuleName) . '/language');
        return $dir . '/' . $locale . '.po';
    }

    public function isStringInFile($string, $filename)
    {
        return 0 < preg_match("/$string/", $this->getFileContents($filename));
    }
    
    public function getFileContents($filename)
    {
        if (!file_exists($filename)) {
            $contents = implode("\n",array( 
                'msgid ""',
                'msgstr ""',
                '"Project-Id-Version: ZendSkeletonApplication\n"',
                '"Report-Msgid-Bugs-To: \n"',
                '"POT-Creation-Date: 2012-07-05 22:32-0700\n"',
                '"PO-Revision-Date: 2012-07-05 23:36-0700\n"',
                '"Last-Translator: Evan Coury <g@gbili.com>\n"',
                '"Language-Team: ZF Contibutors <zf-devteam@zend.com>\n"',
                '"Language: \n"',
                '"MIME-Version: 1.0\n"',
                '"Content-Type: text/plain; charset=UTF-8\n"',
                '"Content-Transfer-Encoding: 8bit\n"',
                '"X-Poedit-KeywordsList: translate\n"',
                '"X-Poedit-Language: Locale\n"',
                '"X-Poedit-Country: COUNTRY\n"',
                '"X-Poedit-Basepath: .\n"',
                '"X-Poedit-SearchPath-0: ..\n"',
            )) . "\n";
            $this->fileAppend($filename, $contents);
            return $contents;
        }
        return file_get_contents($filename, $contents);
    }

    public function fileAppend($filename, $contents)
    {
        return file_put_contents($filename, $contents, FILE_APPEND);
    }
}
