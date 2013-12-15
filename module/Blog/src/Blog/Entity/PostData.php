<?php
namespace Blog\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="post_datas")
 * use repository for handy tree functions
 * @ORM\Entity
 */
class PostData
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="data")
     */
    private $posts;

    /**
     * @ORM\Column(name="title", type="string", length=64)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Media", inversedBy="posts")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $media;

    /**
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setMedia(Media $media = null)
    {
        $this->media = $media;
    }

    public function getMedia()
    {
        return $this->media;
    }

    /**
    * @ORM\PrePersist
    */
    public function setDate(\DateTime $time)
    {
        $this->date = $time;
    }

    /**
    * Get Created Date
    *
    * @return \DateTime
    */
    public function getDate()
    {
        return $this->date;
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
}
