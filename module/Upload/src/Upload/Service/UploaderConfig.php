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
          self::ERROR_MISSING_CONFIG_ALIAS      => "'file_uploader':'%s' config, references an alias. But no 'file_uploader':%s isset.",
    );

    protected $sm;

    protected $config;

    /**
     * Controller specific config
     */
    protected $specificConfig;

    /**
     * aliased config, can be used if some controller specific config key is not set
     * would be better if it was mergeable, to the controller spcific but I dont know how
     * array_merge_recursive adds new arrays when key exists
     */
    protected $aliasedConfig;

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
        $actionRouteParams = $this->getConfigValue('service', 'form_action_route_params', false);
        if ($actionRouteParams) {
            $service->setFormActionRouteParams($actionRouteParams);
        }

        //TODO move this config to the view helper
        $jsScriptPath = $this->getConfigValue('view_helper', 'include_js_script', false);
        if ($jsScriptPath) {
            $service->setIncludeScriptFilePath($jsScriptPath);
        }
        //TODO move this config to the view helper
        $service->displayFormAsPopup($this->getConfigValue('view_helper', 'display_form_as_popup', false));
        //TODO move this to viewHelper config
        $service->setFormInitialStateHidden($this->getConfigValue('view_helper', 'popup_initial_state_hidden', true));
        
        $service->setFileHydrator($this->getServiceFileHydrator());
        $service->setFormName($this->getConfigValue('service', 'form_name', 'file_form'));
        $service->setFileInputName($this->getConfigValue('service', 'file_input_name', 'file_input'));
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

        if (isset($specificConfig['alias'])) {
            $configAlias = $specificConfig['alias'];
            if (!isset($config[$configAlias])) {
                throw new \Exception(sprintf($this->errorMessages[self::ERROR_MISSING_CONFIG_ALIAS], $configKey, $configAlias));
            }
            $this->aliasedConfig  = $config[$configAlias];
        }

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

    protected function getConfigValue($for, $key, $default, $throw=false)
    {
        $config = $this->getControllerSpecificConfig();
        if (isset($config[$for][$key])) {
            $value = $config[$for][$key];
        } else if (isset($this->aliasedConfig[$for][$key])) {
            $value = $this->aliasedConfig[$for][$key];
        } else if ($throw) {
            $message = $default;
            throw new \Exception('Missing config key: ' . $message);
        } else {
            $value = $default;
        }
        return $value;
    }

    public function getServiceFileHydrator()
    {
        $fileHydrator = $this->getConfigValue('service', 'file_hydrator', 'You must set a file hydrator to allow the "uploader" to save entities', $throw=true);
        return $this->sm->get($fileHydrator);
    }

    public function configureControllerPlugin(\Upload\Controller\Plugin\Uploader $plugin)
    {
        $postUploadCallback = $this->getConfigValue('controller_plugin', 'post_upload_callback', false);
        if ($postUploadCallback) {
            if (!is_callable($postUploadCallback)) {
                throw new \Exception('post_upload_callback is not callable');
            }
            $plugin->setPostUploadCallback($postUploadCallback);
        }

        $routeSuccess = $this->getConfigValue('controller_plugin', 'route_success', false);
        if ($routeSuccess) {
            $plugin->setRouteSuccessParams($routeSuccess);
        }

        $plugin->setService($this->getServiceLocator()->get('Upload\Service\Uploader'));
    }
}
