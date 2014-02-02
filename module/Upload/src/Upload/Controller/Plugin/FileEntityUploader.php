<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Upload\Controller\Plugin;

/**
 *
 */
class FileEntityUploader extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * Upload action
     * @return mixed
     */
    public function __invoke(array $config)
    {
        if (!isset($config['file_hydrator'])) {
            throw new \Exception('You must set a file hydrator to allow the fileEntityUploader to save entities');
        }
        $fileHydrator       = $config['file_hydrator'];

        $routeSuccess       = ((isset($config['route_success']))?$config['route_success']:null);
        $routeSuccessParams = ((isset($config['route_success_params']))?$config['route_success_params'] : array());
        $routeSuccessReuse  = ((isset($config['route_success_reuse']))?$config['route_success_reuse'] : true);

        if (isset($config['post_upload_callback'])) {
            if (!is_callable($config['post_upload_callback'])) {
                throw new \Exception('post_upload_callback is not callable');
            }
            $uploadCallback = $config['post_upload_callback'];
        }

        $controller = $this->getController();

        $fileUploader = new \Upload\Service\FileEntityUploader();
        $fileUploader->setFileHydrator($fileHydrator)
                     ->setFormName('file_form')
                     ->setFileInputName('file_input');

        if ($controller->getRequest()->isPost()) {
            $fileUploader->setRequest($controller->getRequest())
                         ->setEntityManager($controller->em());

            $fileUploader->uploadFiles();

            if (isset($uploadCallback)) {
                call_user_func($uploadCallback, $fileUploader, $controller);
            }

            if ($fileUploader->areAllFilesUploaded()) {
                if (!$fileUploader->isAjax()) {
                    return $controller->redirect()->toRoute($routeSuccess, $routeSuccessParams, $routeSuccessReuse);
                }
                return new \Zend\View\Model\JsonModel(array(
                    'status' => true,
                    'formData' => $fileUploader->getPostData(),
                ));
            }

            $messages = $fileUploader->getMessages();

            if ($fileUploader->isAjax()) {
                return new \Zend\View\Model\JsonModel(array(
                    'status' => false,
                    'messages' => ((isset($messages))? $messages : array()),
                    'formData' => $fileUploader->getPostData(),
                ));
            }
        }

        return new \Zend\View\Model\ViewModel(array(
            'fileUploader' => $fileUploader,
            'messages' => ((isset($messages))? $messages : array()),
            'form' => $fileUploader->getFormCopy(),
        ));
    }
}
