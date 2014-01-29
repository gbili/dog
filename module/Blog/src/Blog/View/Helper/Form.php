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
class Form extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke(\Zend\Form\Form $form, $firstRendering = true)
    {
        $view = $this->view;
        $html = '';

        $html .= $view->form()->openTag($form);
        foreach ($view->elementsFlatArray($form) as $element) {
            $html .= $view->renderElement($element, $firstRendering);
        } 
        $html .= $view->form()->closeTag();

        return $html;
    }
}
