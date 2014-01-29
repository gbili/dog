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
class Notify extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke(array $messages)
    {
        if (empty($messages)) {
            return '';
        }

        $messageTextOrMessageArray = current($messages);
        if (is_string($messageTextOrMessageArray)) {
            $messages = array($messages);
        }

        return $this->notifyMessages($messages);
    }

    public function notifyMessages(array $messages)
    {
        $html = '';
        foreach ($messages as $message) {
           $class = key($message);
           $text = current($message);
           $html .= $this->view->message($class, $this->view->translate($text));
        } 
        return $html;
    }
}
