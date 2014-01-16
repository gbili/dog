<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller\Plugin;

/**
 *
 */
class Nonce extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    protected $routeParamName = 'fourthparam';
    protected $validator;

    /**
     * Grabs a param from route match by default.
     *
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function __invoke($paramName=null)
    {
        if (null !== $paramName) {
            return $this->setRouteParamName($paramName);
        }
        return $this;
    }

    /**
     *
     */
    public function isValid()
    {
        return $this->getValidator()->isValid($this->getParam());
    }

    public function getParam()
    {
        $controller = $this->getController();
        if (!$controller || !method_exists($controller, 'plugin')) {
            throw new \Zend\Mvc\Exception\DomainException('Redirect plugin requires a controller that defines the plugin() method');
        }

        $nonce = $controller->plugin('params')->fromRoute($this->routeParamName, false);
        if (!$nonce) {
            throw new Exception\BadRequestException('Missing nonce param, using param name:');
        }
        return $nonce;
    }

    public function getHash()
    {
        return $this->getValidator()->getHash();
    }

    public function setRouteParamName($name)
    {
        $this->routeParamName = $name;
        return $this;
    }

    public function getValidator()
    {
        if (null === $this->validator) {
            $this->validator = new \Zend\Validator\Csrf;
            $this->validator->setSalt($this->getSalt());
        }
        return $this->validator;
    }

    public function getSalt()
    {
        $user = $this->getController()->identity();
        $uid = $user->getId();

        preg_match_all('/(?<alnum>[a-z0-9]+)/', $user->getPassword(), $matches);

        $passwordAlnum = implode('', $matches['alnum']);

        return $uid . $passwordAlnum;
    }
}
