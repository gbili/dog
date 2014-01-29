<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Blog\View\Helper;

/**
 * View helper for translating messages.
 */
class FormElement extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke(\Zend\Form\Element $element, $firstRendering = true)
    {
        return $this->render($element, $firstRendering);
    }


    public function render(\Zend\Form\Element $element, $firstRendering = true)
    {
        $this->translatePlaceholderAttribute($element);
        $errors = $this->renderTranslatedErrors($element);
        $hasErrors = ('' !== ($errors));
        $statusClass = (($hasErrors)? ' has-error' : (($firstRendering)? '' : ' has-success'));

        $controlsDivClass = $this->getElementOption($element, 'controls_div_class', '');
        $helperMethod = $this->getElementOption($element, 'helper_method', 'formElement');

        return "<div class=\"form-group$statusClass\">"
                    . $this->renderTranslatedLabel($element)
                    . "<div class=\"controls $controlsDivClass\">"
                        . $this->view->$helperMethod($element)
                    . '</div>'
                    . $errors
             . '</div>';
    }

    public function getElementOption($element, $optionName, $return = '')
    {
        $options = $element->getOptions();
        if (isset($options[$optionName])) {
            return $options[$optionName];
        }
        return $return;
    }

    public function translatePlaceholderAttribute(\Zend\Form\Element $element)
    {
       $attributes = $element->getAttributes();
       if (!isset($attributes['placeholder'])) {
           return;
       }

       $placeholder = $attributes['placeholder'];
       $element->setAttribute('placeholder', $this->view->translate($placeholder));
    }

    public function renderTranslatedErrors(\Zend\Form\Element $element)
    {   
        $errors = $element->getMessages();
        if (empty($errors)) {
            return '';
        }
        $translatedErrors = array();
        foreach ($errors as $error) {
            $translatedErrors[] = $this->view->translate(current($errors));
        }

        $labelFor = $element->getAttribute('name');
        $errorsString = implode(', ' , $translatedErrors);

        return "<label class=\"control-label\" for=\"$labelFor\">$errorsString</label>";
    }

    public function renderTranslatedLabel(\Zend\Form\Element $element)
    {
        if (null === $element->getLabel()) { 
            return '';
        }
        $element->setLabel($this->view->translate($element->getLabel()));
        return $this->view->formLabel($element);
    }
}
