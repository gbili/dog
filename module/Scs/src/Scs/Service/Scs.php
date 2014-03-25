<?php
namespace Scs\Service;

/**
 *
 *
 */
class Scs
{
    protected $langService;
    protected $translatorService;
    protected $textdomainService;
    protected $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
        $this->translatorService = $sm->get('MvcTranslator');
        $this->langService = $sm->get('lang');
        $this->textdomainService = $sm->get('textdomain');
    }

    public function getCategoriesToTranslated($valuesUcfirsted=false)
    {
        $translated = array();
        $lang = $this->langService->getLang();
        $textdomain = $this->textdomainService->getTextdomain();
        foreach ($this->getCategories() as $category) {
            $translated[$category] = $this->translatorService->translate((($valuesUcfirsted)? ucfirst($category) : $category ), $textdomain, $lang);
        }
        return $translated;
    }

    public function getTranslatedToCategories()
    {
        return array_flip($this->getCategoriesToTranslated());
    }

    public function getCategories()
    {
        return array('symptom', 'cause', 'solution');
    }

    public function getAllowedParents($category=null)
    {
        $categoriesToAllowedParents = array(
            'symptom' => array(),
            'cause' => array('symptom', 'cause'),
            'solution' => array('cause'),
        );
        if (null === $category) {
            return $categoriesToAllowedParents;
        }
        return $categoriesToAllowedParents[$category];
    }

    public function getAllowedChildren($category=null)
    {
        $categoriesToAllowedChildren = array(
            'symptom' => array('cause'),
            'cause' => array('cause', 'solution'),
            'solution' => array(),
        );
        if (null === $category) {
            return $categoriesToAllowedChildren;
        }
        return $categoriesToAllowedChildren[$category];
    }

    public function isAllowedChild($parent, $child)
    {
        return in_array($child, $this->getAllowedChildren($parent));
    }

    public function isAllowedParent($parent, $child)
    {
        return in_array($parent, $this->getAllowedParents($child));
    }
}
