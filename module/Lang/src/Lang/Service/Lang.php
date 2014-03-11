<?php
namespace Lang\Service;

class Lang
{
    protected $application = null;
    protected $defaultLang = 'en';

    public function __construct(\Zend\Mvc\Application $application)
    {
        $this->application = $application;
    }

    public function getLang()
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();

        if (null === $routeMatch) {
            $config = $this->application->getServiceManager()->get('config');
            if (isset($config['lang']) && isset($config['lang']['default_lang'])) {
                return $config['lang']['default_lang'];
            }
            return $this->defaultLang;
        }

        $langParam = $routeMatch->getParam('lang');
        if (null === $langParam) {
            throw new \Exception('lang param not set yet');
        }
        return $langParam;
    }

    public function getAllLangs()
    {
        return $this->getLangsAvailable();
    }

    public function getLangsAvailable()
    {
        $config = $this->application->getServiceManager()->get('config');
        if (isset($config['lang']) && isset($config['lang']['langs_available'])) {
            return $config['lang']['langs_available'];
        }
        return array($this->getLang());
    }

    /**
     * Return the date time format for the current lang
     */
    public function getDateTimeFormat()
    {
        $config = $this->application->getServiceManager()->get('Config');
        $lang = $this->getLang();
        if (isset($config['lang']['date_time_formats'][$lang])) {
            return $config['lang']['date_time_formats'][$lang];
        }
        return \DateTime::ISO8601;
    }

    public function getStandardDate($value)
    {
        $format = $this->getDateTimeFormat();
        $namedPatterns = array(
            'dd' => '(?P<dd>[0-9]{2})',
            'mm' => '(?P<mm>[0-9]{2})',
            'yy' => '(?P<yy>[0-9]{4})',
        );
        $patterns = array();
        $replacements = array();
        foreach ($namedPatterns as $name => $replacement) {
            $patterns[] = "/$name/";
            $replacements[] = $replacement;
        }
        preg_replace($patterns, $replacements, '#'. $format . '#');
        $regexFormat = '#' . preg_replace($patterns, $replacements, $format) . '#';

        if (!(0 < preg_match_all($regexFormat, $value, $matches))) {
            return false;
        }
        $day = current($matches['dd']);
        $month = current($matches['mm']);
        $year = current($matches['yy']);

        $isoDate = "$year-$month-$day";
        return $isoDate;
    }

    public function getLocale()
    {
        throw new \Exception('use getLang() instead of getLocale()');
    }
}
