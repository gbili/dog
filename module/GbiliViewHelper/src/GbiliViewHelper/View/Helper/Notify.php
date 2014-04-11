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
class Notify extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Because we want to support the helper_name
     * feature on a message list level we will
     * have to store each list separatedly
     * array(messageList1, messageList2 etc.)
     */
    protected $messagesListList = array();

    /**
     * Queue messages for later rendering
     * If no parameter is passed, the helper will be returned
     * you can then call, notifyMesages($messages) directly
     * or you can call renderAll() to get all the stacked
     * messages
     *
     * To stack a message list to be rendered, use
     * the invoke method with the list as param
     *
     * @return string
     */
    public function __invoke($messages = null)
    {
        if (null === $messages) {
            return $this;
        }

        if (!is_array($messages)) {
            throw new \Exception('$messages param should be an array of messages');
        }

        if (empty($messages)) {
            return;
        }

        if ($this->isMessageAndNotMessageList($messages)) {
            $messages = array($messages);
        }

        $this->messagesListList[] = $messages;
    }

    public function renderAll()
    {
        $html = '';
        foreach ($this->messagesListList as $messages) {
            $html .= $this->notifyMessages($messages);
        }
        return $html;
    }

    public function isMessageAndNotMessageList(array $messages)
    {
        $messageTextOrMessageArray = current($messages);
        return is_string($messageTextOrMessageArray);
    }

    public function notifyMessages(array $messages)
    {
        $helperName = 'simpleMessage';
        if (isset($messages['helper_name'])) {
            $helperName = $messages['helper_name'];
            unset($messages['helper_name']);
        }

        $html = '';
        foreach ($messages as $message) {
           $html .= $this->view->{$helperName}($message);
        } 
        return $html;
    }
}
