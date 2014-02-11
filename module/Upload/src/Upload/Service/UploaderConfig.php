<?php
namespace Upload\Service;

class UploaderConfig implements UploaderServiceConfigInterface, UploaderControllerPluginConfigInterface
{
    const DEFAULT_CONFIG_KEY              = 'default';

    const ERROR_MISSING_CONTROLLER_CONFIG = 0;
    const ERROR_MISSING_DEFAULT_CONFIG    = 1;
    const ERROR_MISSING_CONFIG_ALIAS      = 2;

    protected $errorMessages = array(
          self::ERROR_MISSING_CONTROLLER_CONFIG => 'Controller impelements \Upload\ConfigKeyAwareInterface, but no controller specific configuration was found',
          self::ERROR_MISSING_DEFAULT_CONFIG    => 'There is no controller specific config, nor a default config',
          self::ERROR_MISSING_CONFIG_ALIAS      => "'file_uploader':'%s' config value, is string. So alias is expected. But no 'file_uploader':%s isset.",
    );

    protected $sm;

    protected $config;

    /**
     * Controller specific config
     */
    protected $specificConfig;

    protected $controllerKey;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function setServiceLocator($sm)
    {
        $this->sm = $sm;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->sm;
    }

    public function setControllerKey($controllerKey)
    {
        $this->controllerKey = $controllerKey;
        return $this;
    }

    public function getControllerKey()
    {
        if (null === $this->controllerKey) {
            throw new \Exception('Controller key not set, trying to use service before event dispatch?');
        }
        return $this->controllerKey;
    }

    public function configureService(\Upload\Service\Uploader $service)
    {
        $config = $this->getControllerSpecificConfig();
        $serviceConfig = $config['service'];

        if (isset($serviceConfig['form_action_route_params'])) {
            $service->setFormActionRouteParams($serviceConfig['form_action_route_params']);
        }

        if (isset($serviceConfig['include_js_script'])) {
            $service->setIncludeScriptFilePath($serviceConfig['include_js_script']);
        }
        
        $service->setFileHydrator($this->getServiceFileHydrator());
        $service->setFormName($this->getServiceFormName());
        $service->setFileInputName($this->getServiceFileInputName());
    }

    public function getControllerSpecificConfig()
    {
        if (null !== $this->specificConfig) {
            return $this->specificConfig;
        }

        $configKey = $this->getConfigKey();
        $config    = $this->config;

        if (!isset($config[$configKey])) {
            throw new \Exception(
                $this->errorMessages[(($configKey === self::DEFAULT_CONFIG_KEY)? self::ERROR_MISSING_DEFAULT_CONFIG : self::ERROR_MISSING_CONTROLLER_CONFIG)]
            );
        }

        $specificConfig = $config[$configKey];

        $this->specificConfig = $specificConfig;

        return $specificConfig; 
    }

    public function getConfigKey()
    {   
        $config    = $this->config;
        $configKey = $this->getControllerKey();

        if (!isset($config[$configKey])) {
            $configKey = self::DEFAULT_CONFIG_KEY;
        }
        if (is_string($config[$configKey])) {
            $configAlias = $config[$configKey];
            if (!isset($config[$configAlias])) {
                throw new \Exception(sprintf($this->errorMessages[self::ERROR_MISSING_CONFIG_ALIAS], $configKey, $configAlias));
            }
            $configKey = $configAlias;
        }
        return $configKey;
    }

    public function getServiceFormName()
    {
        $config = $this->getControllerSpecificConfig();
        $serviceConfig = $config['service'];
        return ((isset($serviceConfig['form_name']))? $serviceConfig['form_name'] : 'file_form');
    }

    public function getServiceFileInputName()
    {
        $config = $this->getControllerSpecificConfig();
        $serviceConfig = $config['service'];
        return ((isset($serviceConfig['file_input_name']))? $serviceConfig['file_input_name'] : 'file_input');
    }

    public function getServiceFileHydrator()
    {
        $config = $this->getControllerSpecificConfig();
        $serviceConfig = $config['service'];
        if (!isset($serviceConfig['file_hydrator'])) {
            throw new \Exception('You must set a file hydrator to allow the "uploader" to save entities');
        }
        return $this->sm->get($serviceConfig['file_hydrator']);
    }

    public function configureControllerPlugin(\Upload\Controller\Plugin\Uploader $plugin)
    {
        $config = $this->getControllerSpecificConfig();
        $pluginConfig = $config['controller_plugin'];

        if (isset($pluginConfig['post_upload_callback'])) {
            if (!is_callable($pluginConfig['post_upload_callback'])) {
                throw new \Exception('post_upload_callback is not callable');
            }
            $plugin->setPostUploadCallback($pluginConfig['post_upload_callback']);
        }

        if (isset($pluginConfig['route_success'])) {
            $plugin->setRouteSuccessParams($pluginConfig['route_success']);
        }

        $plugin->setService($this->getServiceLocator()->get('Upload\Service\Uploader'));
    }
}
