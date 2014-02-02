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
class AjaxFileUploadProgress extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke()
    {
        return $this;
    }

    public function getScriptFilename()
    {
        return 'ajax.file_upload.js.phtml';
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
}
