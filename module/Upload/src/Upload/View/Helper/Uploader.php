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
    protected $areScriptsRegistered = false;
    protected $popupDivId = 'gbiliuploader-upload-popup';

    /**
     * Translate a message
     * @return string
     */
    public function __invoke()
    {
        $html = $this->renderHtml();

        if (!$this->areScriptsRegistered) {
            $this->registerScripts();
        }

        return $html;
    }

    /**
     * The script is actually not fully js, there needs to be som php
     * execution first, that's why its phtml and not js
     *
     */
    public function registerScripts()
    {
        $view = $this->view;

        require_once realpath(__DIR__ . '/../../../../view/partial') . '/ajax.file_upload.js.phtml';

        if ($this->service->hasIncludeScriptFilePath()) {
            require_once $this->service->getIncludeScriptFilePath();
        }

        $this->areScriptsRegistered = true;
    }

    public function isDisplayedAsPopup()
    {
        return $this->getService()->isFormDisplayedAsPopup();
    }

    public function renderHtml()
    {
        if ($this->isDisplayedAsPopup()) {
            $html = $this->getAllInsidePopup();
        } else {
            $html = $this->renderForm()
                  . $this->renderProgressBar();
        }
        return $html;
    }

    public function getAllInsidePopup()
    {
        $id = $this->popupDivId;
        $hidden = (($this->getService()->isFormInitialStateHidden())? 'hidden' : '');

        return "<div id=\"$id\" class=\"$hidden\">"
                 . '<a class="gbiliuploader-hide-popup-button">✖</a>'
                 . $this->renderForm() 
                 . $this->renderProgressBar() 
                 . '<div class="messages"></div>'
             . '</div>';
    }

    public function renderProgressBar()
    {
        return '<div id="gbiliuploader-progress">'
                   . '<div class="progress help-block">'
                      . '<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>'
                   . '</div>'
                   . '<p></p>'
             . '</div>';
    }

    public function renderForm()
    {
        if (null !== $this->formHtml) {
            return $this->formHtml;
        }

        $view    = $this->getView();
        $service = $this->getService();
        $form    = $service->getForm();

        if (!$form->hasAttribute('action')) {
            $actionRouteParams = $service->getFormActionRouteParams();
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

        $this->formHtml = $view->renderForm($form);

        return $this->formHtml;
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
