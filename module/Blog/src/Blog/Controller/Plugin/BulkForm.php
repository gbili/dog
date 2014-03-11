<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Blog\Controller\Plugin;

class BulkForm extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * @param boolean $populateMulticheck, multicheck needs not be 
     * populated if there is no validation process, it will be populated
     * in the view
     */
    public function __invoke($populateMulticheck = false)
    {
        $controller        = $this->controller;

        $parts             = explode('\\', get_class($controller));
        $baseNamespace     = current($parts);
        $entityBulk        = str_replace('Controller', 'Bulk', end($parts));
        $bulkFormClassname = "\\$baseNamespace\\Form\\$entityBulk";

        $bulkForm          = new $bulkFormClassname('bulk-action');

        if (!$populateMulticheck) {
            return $bulkForm;
        }

        $valueOptions = array();
        foreach ($controller->getEntities() as $post) {
            $valueOptions[] = array('label' => '', 'value' => ((is_array($post))? $post['id'] : $post->getId()));
        }

        $multicheckElementName = $bulkForm->getOption('multicheck_element_name');
        $bulkForm->get($multicheckElementName)->setValueOptions($valueOptions);
        return $bulkForm;
    }
}
