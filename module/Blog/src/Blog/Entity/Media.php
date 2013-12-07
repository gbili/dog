<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="medias")
 */
class Media 
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
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;

    /**
     * Title
     * @ORM\Column(name="alt", type="string", length=64)
     */
    private $alt;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * The media is linked to this post
     * @ORM\OneToMany(targetEntity="Post", mappedBy="medias", cascade={"persist"})
     */
    private $posts;

    /**
     * @ORM\Column(name="width", type="integer")
     */
    private $width;

    /**
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    /**
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    public function getAlt()
    {
        return $this->alt;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function getPosts()
    {
        return $this->posts;
    }

    public function addPosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $post->setMedia($this);
            $this->posts->add($post);
        }
    }

    public function removePosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $post->setMedia(null);
            $this->posts->removeElement($post);
        }
    }
}
