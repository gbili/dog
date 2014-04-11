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
class Message extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke($class, $message, $former = null)
    {
        if (null !== $former) {
            $message = "<strong>$former</strong>, $message";
        }

        return "<div class=\"alert alert-dismiss alert-$class\">"
               . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' 
               . "<p>$message</p>"
             . '</div>';
    }
}
