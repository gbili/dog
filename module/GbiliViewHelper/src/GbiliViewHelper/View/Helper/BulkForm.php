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
 * View helper for translating messages.
 */
class BulkForm extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Automatically fetched from the form view variable
     * if not previously set and if available
     *
     * @var \GbiliViewHelper\Form\Bulk
     */
    protected $form;

    /**
     * @var string the multicheck input element name with []
     * this is retrieved from the form
     */
    protected $multicheckElementName;

    /**
     * Translate a message
     * @return string
     */
    public function __invoke()
    {
        return $this;
    }

    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    public function prepare($routeName=null, array $params=array('action' => 'bulk'), $reuseMatchedParams=true)
    {
        $form = $this->getForm();
        $view = $this->view;

        $form->setAttribute('action', $view->url($routeName , $params, $reuseMatchedParams));
        $form->setAttribute('role', 'form');
        $view->formActionPrepare($form, 'form-inline', true);
    }

    public function getForm()
    {
        if (null === $this->form) {
            if (!isset($this->view->form)) {
                throw new \Exception('No $form available in view');
            }
            $this->setForm($this->view->form);
        }
        return $this->form;
    }

    public function getMulticheckElementName()
    {
        if (null === $this->multicheckElementName) {
            $this->multicheckElementName = $this->getForm()->getOption('multicheck_element_name') . '[]';
        }
        return $this->multicheckElementName;
    }

    public function renderSelectAllCheckbox()
    {
        return '<label class="checkbox-inline" for="bulk-action-input-all">'
                 . '<input id="bulk-action-input-all" type="checkbox">' . $this->view->translate('All') 
             . '</label>';
    }

    public function renderElementCheckbox($elementId)
    {
        return '<input type="checkbox" name="' . $this->getMulticheckElementName() .'" value="' . $elementId .'">';
    }

    public function getPartialScriptPath()
    {
        return realpath(__DIR__ . '/../../../../view/partial') . '/bulk-action.js.phtml';
    }
}
