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
class RenderNavigation extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Render the navigation from a partial
     * 
     * @return array|string
     */
    public function __invoke($navigationNames, $partial, $returnArray = false)
    {
        if (!is_array($navigationNames)) {
            $navigationNames = array($navigationNames);
        }

        $navigations = array();
        foreach ($navigationNames as $container => $name) {
            $container = ((is_string($container))? $container : $name);
            $navigations[] = $this->renderNavigation($container, $name, $partial);
        }
        return (($returnArray)? $navigations : implode('', $navigations));
    }

    /**
     *
     * @return
     */
    public function renderNavigation($container, $navigationName, $partial)
    {
        $view = $this->view;
        $view->navigation($container)->menu()->setPartial(array($partial, $navigationName));
        return $view->navigation($container)->menu()->render();
    }
}
