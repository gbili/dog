<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Dogtore\View\Helper;

/**
 * View helper for translating messages.
 */
class PostButtonsGenerator extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translate a message
     * @return string
     */
    public function __invoke(array $post, $type = null)
    {
        $buttons = '';
        //if (null !== $type || 'edit' === $type) {
        $buttons .= $this->getEditButton($post);
        
        //if ($type == 'relations' || (null !== $type)) {
        $buttons .= $this->getRelatedButtons($post);
        return $buttons;
    }

    protected function getEditButton(array $post)
    {
        $user = $this->view->identity();
        if (!$user || $post['owner_uniquename'] !== $user->getUniquename()) {
            return '';
        }
        $url = $this->view->url('blog', array('controller' => 'post', 'action' => 'edit', 'id' => $post['post_id']), true);
        $text = $this->view->translate('Edit', 'dogtore');
        return $this->getButton($url, $text, 'default');
    }

    protected function getButton($url, $text, $cssclass='default')
    {
        return "<a href=\"$url\" class=\"btn btn-$cssclass\" role=\"button\">$text</a>";
    }

    protected function getRelatedButtons(array $post)
    {
        $buttons = '';
        if (null !== $post['parent_post_slug']) {
            $url = $this->view->url(null, array('post_slug' => $post['post_slug'], 'related' => 'parent'), true);

            $patterns = array('category');
            $replacements = array($post['parent_lvl1_category_name']);
            $text = '&lt; ' . $this->view->patternTranslate($patterns, $replacements, "View category", 'dogtore');

            $buttons .= $this->getButton($url, $text, $this->view->cssClass($post['parent_lvl1_category_slug']));
        }
        if (null !== $post['child_post_slug']) {
            $url = $this->view->url(null, array('post_slug' => $post['post_slug'], 'related' => 'children'), true);

            $patterns = array('category');
            $replacements = array($post['child_lvl1_category_name']);
            $text = $this->view->patternTranslate($patterns, $replacements, "View category", 'dogtore'). ' &gt;';

            $buttons .= $this->getButton($url, $text, $this->view->cssClass($post['child_lvl1_category_slug']));
        }
        return $buttons;
    }
}
