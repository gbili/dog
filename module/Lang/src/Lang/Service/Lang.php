<?php
namespace Lang\Service;

class Lang
{
    protected $application = null;

    public function __construct(\Zend\Mvc\Application $application)
    {
        $this->application = $application;
    }

    public function getLang()
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();
        if (null === $routeMatch) {
            throw new \Exception('RouteMatch not matched');
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
