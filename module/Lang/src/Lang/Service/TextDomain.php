<?php
namespace Lang\Service;

class TextDomain 
{
    protected $textDomain;
    protected $defaultTextDomain = 'application';

    protected $sm;

    public function __construct($sm = null)
    {
        if (null !== $sm) {
            $this->setServiceManager($sm);
        }
    }

    public function getTextDomain()
    {
        if (null !== $this->textDomain) {
            return $this->textDomain;
        }
        return $this->getDefaultTextDomain();
    }

    public function getServiceManager()
    {
        if (null === $this->sm) {
            throw new \Exception('Sm not set');
        }
        return $this->sm;
    }

    public function setServiceManager($sm)
    {
        $this->sm = $sm;
        return $this;
    }

    public function getRegisteredModules()
    {
        $sm = $this->getServiceManager();
        $config = $sm->get('ApplicationConfig');
        return $config['modules'];
    }

    public function setController($controller)
    {
        if ($controller instanceof \Zend\Mvc\Controller\AbstractActionController) {
            $baseNamespace = current(explode('\\', get_class($controller)));
            if (in_array($baseNamespace, $this->getRegisteredModules())) {
                $this->textDomain = strtolower($baseNamespace);
            }
        }
    }

    public function getDefaultTextDomain()
    {
        $configTextDomain = $this->getConfigDefaultTextDomain();
        if (null !== $configTextDomain) {
            return $configTextDomain;
        }
        return $this->defaultTextDomain;
    }

    public function getConfigDefaultTextDomain()
    {
        $sm = $this->getServiceManager();
        $config = $sm->get('Config');
        if (isset($config['lang']) && isset($config['lang']['default_textdomain'])) {
            return $config;
        }
        return null;
    }
}
