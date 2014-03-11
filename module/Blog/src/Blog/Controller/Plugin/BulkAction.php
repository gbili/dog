<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Blog\Controller\Plugin;

class BulkAction extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * Nonce based delete action
     * @return mixed
     */
    public function __invoke($onSuccessRedirectRoute, array $params=array())
    {
        $controller = $this->controller;

        if (!$controller->getRequest()->isPost()) {
            throw new \Exception('500 Access denied, not intended to be used if not post');
        }

        $form = $controller->bulkForm(true);
        $form->setData($formData = $controller->getRequest()->getPost());

        if (!$form->isValid()) {
            throw new \Exception('Invalid form provided');
        }

        $formValidData         = $form->getData();
        $action                = $form->getSelectedAction();
        $multicheckElementName = $form->getOption('multicheck_element_name');

        $controller->$action($formValidData[$multicheckElementName]);

        $controller->flashMessenger()->addMessage($action . ' succeed');

        return $controller->redirect()->toRoute($onSuccessRedirectRoute, $params, true);
    }
}
