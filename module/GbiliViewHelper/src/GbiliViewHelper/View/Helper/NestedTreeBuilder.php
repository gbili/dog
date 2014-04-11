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
class NestedTreeBuilder extends \Zend\View\Helper\AbstractHelper
{
    protected $view;

    protected $treeNodePositionToId = null;

    /**
     *
     * @return string
     */
    public function __invoke(array $nodes, $type = null)
    {
        $this->view = $this->getView();
        if (null === $type) {
            $type = 'ul';
        }
        $method = 'getTreeAs' . ucfirst($type);
        return $this->$method($nodes);
    }

    public function getTreeAsUl(array $nodes)
    {
        $html = '<ul>';
        foreach ($nodes as $node) {
            $html .= '<li>';
            if (isset($node['name'])) {
                 $html .= $this->view->escapeHtml($node['name']);
            }
            if (!empty($node['__children'])) {
                $html .= $this->getTreeAsUl($node['__children']);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function getTreeAsFlatDashedArray(array $nodes, $depth = null)
    {
        if (null === $depth) {
            $depth = 0;
        }
        $return = array();
        $children = null;
        foreach ($nodes as $node) {
            if (isset($node['name'])) {
                 $node['name'] = str_repeat('-', $depth * 7) . ' ' . $this->view->escapeHtml($node['name']);
                 $children = $node['__children'];
                 unset($node['__children']);
                 $return[] = $node;
            }
            if (null !== $children) {
                $return = array_merge($return, $this->getTreeAsFlatDashedArray($children, $depth + 1));
            }
            $children = null;
        }
        return $return;
    }

    public function getTreeAsFlatArray(array $nodes, $depth = null)
    {
        if (null === $depth) {
            $depth = 0;
        }
        $return = array();
        $children = null;
        foreach ($nodes as $node) {
            $children = $node['__children'];
            unset($node['__children']);
            $return[] = $node;
            if (null !== $children) {
                $return = array_merge($return, $this->getTreeAsFlatArray($children, $depth + 1));
            }
            $children = null;
        }
        return $return;
    }
}
