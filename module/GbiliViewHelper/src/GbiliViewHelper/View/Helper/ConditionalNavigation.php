<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace GbiliViewHelper\View\Helper;

/**
 * View helper for displaying side nav.
 */
class ConditionalNavigation extends \Zend\View\Helper\AbstractHelper
{
    protected $serviceLocator;

    protected $containedRouteNames;
    protected $matchedRouteName;

    protected $navigationConfig;

    public function __invoke()
    {
        return $this;
    }

    public function setServiceLocator($sm)
    {
        $this->serviceLocator = $sm;
        return $this;
    }

    /**
     * This serves when trying to check whether the parent 
     * navigation should make the page with $route active
     *
     * For example the top navigation may have a button
     * that brings to a page where a side navigation is shown
     * Its necessary to test whether any of the side navigation
     * buttons route is being shown. Because it's possible
     * that the side navigation has no route in common with 
     * the top navigation. And still we need to make the top
     * nav button active if any of the side navigation buttons
     * is being showm.
     * To do that, we test whether the parent nav route is contained
     * in the sub navigation. And on top of that, we check if any of
     * sub container routes is active
     */
    public function isRouteContainedAndContainerActive($routeName, $containers)
    {
        return $this->isRouteContained($routeName, $containers) && $this->isMatchedRouteContained($containers);
    }

    public function isMatchedRouteContained($containers)
    {
        return $this->isRouteContained($this->getMatchedRouteName(), $containers);
    }

    public function isRouteContained($routeName, $containers)
    {
        if (!is_array($containers)) {
            $containers = array($containers);
        }

        foreach ($containers as $container) {
            if (in_array($routeName, $this->getContainedRouteNames($container))) return true;
        }
        return false;
    }

    protected function getNavigationConfig()
    {
        if (null === $this->navigationConfig) {
            $config = $this->serviceLocator->get('Config');
            $this->navigationConfig = $config['navigation'];
        }
        return $this->navigationConfig;
    }

    protected function getContainedRouteNames($containerName)
    {
        if (isset($this->containedRouteNames[$containerName])) {
            return $this->containedRouteNames[$containerName];
        }
        $navigationConfig = $this->getNavigationConfig();

        if (!isset($navigationConfig[$containerName])) {
            return array();
        }
        $containerConfig = $navigationConfig[$containerName];

        $containerRouteNames = $this->getContainerRouteNamesRecursive($containerConfig);

        $this->containedRouteNames[$containerName] = $containerRouteNames;
        return $containerRouteNames;
    }

    /**
     * @param array $container a navigation config 
     */
    protected function getContainerRouteNamesRecursive($container)
    {
        $routenames = array();
        foreach ($container as $containedItem) {
            if (!(is_array($containedItem) && isset($containedItem['route']))) {
                throw new \Exception('Navigation config not properly formatted');
            }
            $routenames[] = $containedItem['route'];
            if (isset($containedItem['pages'])) {
                $routenames = array_merge($routenames, $this->getContainerRouteNamesRecursive($containedItem['pages']));
            }
        }
        return $routenames;
    } 

    protected function getMatchedRouteName()
    {
        if (null !== $this->matchedRouteName) {
            return $this->matchedRouteName;
        }

        $routeMatch = $this->serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();
        if (null === $routeMatch) {
            throw new \Exception('Route Not matched');
        }
        $this->matchedRouteName = $routeMatch->getMatchedRouteName();
        return $this->matchedRouteName;
    }
}
