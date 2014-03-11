<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller\Plugin;

/**
 *
 */
class RouteParamsTransformer extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * Get param from route. And return a string()
     * plugin instance.
     * However if param does not exist return self
     *
     *
     * @param string $paramname the route param name
     * @return ExpressivePatternTransform or RouteParamsTransformr
     */
    public function __invoke($paramname)
    {
        $controller = $this->getController();
        $param = $controller->params()->fromRoute($paramname, false);

        return (($param)? $controller->string($param) : $this);
    }

    /**
     * Called when route param is false.
     *
     * This plugin uses the string() plugin to transform route
     * params into whatever. It is used like this:
     *     $controller->thisPlugin('route_param_name')->underscoreToSpace();
     * In this expression, the call to underscoreToSpace() will be made to 
     * the string() plugin. However in case there is no such a param we trap
     * the call to unserscoreToSpace here and return false.
     */
    public function __call($method, $params)
    {
        return false;
    }
}
