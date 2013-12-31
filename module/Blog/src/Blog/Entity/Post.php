<?php
namespace Blog\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="posts")
 *  use repository for handy tree functions
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Post
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="slug", type="string", length=64)
     */
    private $slug;

    /**
     * @ORM\Column(name="locale", type="string", length=64)
     */
    private $locale;

    /**
     * @ORM\ManyToOne(targetEntity="TranslatedPost", inversedBy="translations")
     * @ORM\JoinColumn(name="translatedpost_id", referencedColumnName="id")
     */
    private $translated;

    /**
     * @ORM\ManyToOne(targetEntity="PostData", inversedBy="posts")
     * @ORM\JoinColumn(name="data_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $data;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setData(PostData $postData)
    {
        $this->data = $postData;
    }

    public function getData()
    {
        if (null === $this->data) {
            return new PostData();
        }
        return $this->data;
    }

    /**
     * Proxy for post data
     */
    public function __call($method, $params)
    {
        if (!method_exists($this->getData(), $method)) {
            throw new \Exception('Call to undefined method: ' . $method);
        }
        return ((!empty($params))? $this->getData()->$method(current($params)) : $this->getData()->$method());
    }

    public function setCategory(Category $category)
    {
        $this->reuseLocales($this, $category);
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setParent(Post $parent = null)
    {
        $this->parent = $parent;    
    }

    public function getParent()
    {
        return $this->parent;   
    }

    public function setRoot(Post $root = null)
    {
        return $this->root;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(Post $child)
    {
        $this->reuseLocales($this, $child);
        $child->setParent($this);
        $this->children->add($child);
    }

    public function addChildren(\Doctrine\Common\Collections\Collection $children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function removeChild(Post $child)
    {
        $child->setParent(null);
        $this->children->removeElement($child);
    }

    public function removeChildren(\Doctrine\Common\Collections\Collection $children)
    {
        foreach ($children as $child) {
            $this->removeChild($child);
        }
    }

    public function setTranslated(Post $translated = null)
    {
        $this->translated = $translated;    
    }

    public function getTranslated()
    {
        return $this->translated;   
    }

    public function setUser(\User\Entity\User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function hasLocale()
    {
        return null !== $this->locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function reuseLocales($one, $other)
    {
        if (!$one->hasLocale() && !$other->hasLocale()) {
            return;
        }
        if ($one->hasLocale() && $other->hasLocale()) {
            if ($one->getLocale() !== $other->getLocale()) {
                throw new \Exception('Post locale and post data locale cannot be different');
            }
            return;
        }
        if ($one->hasLocale()) {
            $other->setLocale($one->getLocale());
        } else {
            $one->setLocale($other->getLocale());
        }
    }
}
