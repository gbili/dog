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
     * @ORM\Column(name="locale", type="string", length=64)
     */
    private $locale;

    /**
     * @ORM\ManyToOne(targetEntity="TranslatedPostData", inversedBy="translations")
     * @ORM\JoinColumn(name="translated_postdata_id", referencedColumnName="id")
     */
    private $translated;

    /**
     * A post data can be used in many posts, this would avoid
     * useless text duplication
     *
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
        if ($media !== null) {
            $this->reuseLocales($media, $this);
        }
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
        $this->reuseLocales($this, $post);
        $post->setData($this);
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
        $post->setData(null);
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

    public function setTranslated(Post $translated = null)
    {
        $this->translated = $translated;    
    }

    public function getTranslated()
    {
        return $this->translated;   
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
