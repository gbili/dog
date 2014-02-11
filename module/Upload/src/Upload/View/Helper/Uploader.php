<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Upload\View\Helper;

/**
 * View helper for translating messages.
 */
class Uploader extends \Zend\View\Helper\AbstractHelper
{
    protected $service;
    protected $formHtml;

    /**
     * Translate a message
     * @return string
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * The script is actually not fully js, there needs to be som php
     * execution first, that's why its phtml and not js
     *
     */
    public function getScriptPath()
    {
        return realpath(__DIR__ . '/../../../../view/partial') . '/ajax.file_upload.js.phtml';
    }

    public function renderForm()
    {
        if (null !== $this->formHtml) {
            return $this->formHtml;
        }

        $view = $this->getView();

        $form = $this->getService()->getForm();

        if (!$form->hasAttribute('action')) {
            $actionRouteParams = $this->getService()->getFormActionRouteParams();
            $form->setAttribute(
                'action', 
                $view->url(
                    $actionRouteParams['route'], 
                    $actionRouteParams['params'], 
                    $actionRouteParams['reuse_matched_params']
                )
            );
        }
        $form->prepare();

        $html = '';
        $html .= $view->form()->openTag($form);
        $html .= $view->formFileUploadProgress();
        foreach ($form->getElements() as $element) {
            $html .= '<div class="form-group">';
                $html .= ((null !== $element->getLabel())? $view->translate($view->formLabel($element), 'blog') : '');
                $html .= $view->formElement($element); 
            $html .= '</div>';
        }
        $html .=  $view->form()->closeTag(); 
        $view->formHtml = $html;

        return $html;
    }

    public function progressBar()
    {
      ?><div id="progress" class="help-block">
            <div class="progress progress-info progress-striped">
                <div class="bar"></div>
            </div>
            <p></p>
        </div><?php
    }

    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    public function getService()
    {
        return $this->service;
    }
}
