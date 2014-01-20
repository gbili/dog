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
            // throw new \Exception('route not matched, calling get lang too soon');
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

    public function getLocale()
    {
        throw new \Exception('use getLang() instead of getLocale()');
    }
}
