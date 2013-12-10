<?php
namespace Blog\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=64, precision=0, scale=0, nullable=false, unique=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $lft;

    /**
     * @var integer
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $lvl;

    /**
     * @var integer
     *
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $rgt;

    /**
     * @var integer
     *
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $root;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    private $posts;

    /**
     * @var \Blog\Entity\Category
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Category
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add post
     *
     * @param \Blog\Entity\Post $posts
     */
    public function addPost(Post $post)
    {
        $post->setCategory($this);
        $this->posts->add($post);
    }

    public function addPosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->addPost($post);
        }
    }

    /**
     * Remove posts
     *
     * @param \Blog\Entity\Post $posts
     */
    public function removePost(\Blog\Entity\Post $post)
    {
        $this->posts->removeElement($post);
        $post->setCategory(null);
    }

    /**
     * Remove posts
     *
     * @param \Blog\Entity\Post $posts
     */
    public function removePosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->removePost($post);
        }
    }

    /**
     * Set parent
     *
     * @param \Blog\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Blog\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Blog\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(Category $child)
    {
        $child->setParent($this);
        $this->children->add($child);
    }

    public function addChildren(\Doctrine\Common\Collections\Collection $children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function removeChild(Category $child)
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
}
