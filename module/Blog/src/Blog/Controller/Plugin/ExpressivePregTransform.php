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
class ExpressivePregTransform extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    protected $proxy;

    /**
     * @param $string to transform. Allow null, then the string must be passed to underscoredString
     */
    public function __invoke($string = null)
    {
        return $this->getProxy()->setStringToTransform($string);
    }

    public function getProxy()
    {
        if (null === $this->proxy) {
            $this->proxy = new \GbiliViewHelper\Service\ExpressivePregTransform;
        }
        return $this->proxy;
    }
}
