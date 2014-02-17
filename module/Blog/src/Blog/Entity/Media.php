<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="locale", type="string", length=64)
     */
    private $locale;

    /**
     * @ORM\Column(name="publicdir", type="string", length=64)
     */
    private $publicdir;

    /**
     * @ORM\ManyToOne(targetEntity="TranslatedMedia", inversedBy="translations")
     * @ORM\JoinColumn(name="translatedmedia_id", referencedColumnName="id")
     */
    private $translated;

    /**
     * @ORM\Column(name="slug", type="string", length=64)
     */
    private $slug;

    /**
     * Title
     * @ORM\Column(name="alt", type="string", length=64)
     */
    private $alt;

    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * The media is linked to this post
     * @ORM\OneToMany(targetEntity="PostData", mappedBy="media", cascade={"persist"})
     */
    private $posts;

    /**
     * @ORM\ManyToOne(targetEntity="File", inversedBy="medias")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $file;

    /**
     * @ORM\Column(name="width", type="integer", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    private $height;

    /**
     * css percent
     * @ORM\Column(name="csspercent", type="integer", nullable=true)
     */
    private $csspercent;

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

    public function setUser(\User\Entity\User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }


    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getUri()
    {
        return $this->getFile()->getUri();
    }

    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    public function getAlt()
    {
        return $this->alt;
    }

    public function getType()
    {
        return $this->getFile()->getType();
    }

    public function setDescription($description = null)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setWidth($width = null)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setHeight($height = null)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setCsspercent($csspercent = null)
    {
        $this->csspercent = $csspercent;
    }

    public function getCsspercent()
    {
        return $this->csspercent;
    }

    public function getSize()
    {
        return $this->getFile()->getSize();
    }

    public function getPosts()
    {
        return $this->posts;
    }

    public function addPost(Post $post)
    {
        $post->setMedia($this);
        $this->posts->add($post);
    }

    public function addPosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->addPost($post);
        }
    }

    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
        $post->setMedia(null);
    }

    public function removePosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->removePost($post);
        }
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
     * Handy method to get the src attribute
     * @return string
     */
    public function getSrc()
    {
        return $this->getPublicdir() . '/' . $this->getSlug();
    }

    /**
     * Directory from which image is publicly
     * accessible
     */
    public function setPublicdir($publicdir)
    {
        $this->publicdir = $publicdir;
        return $this;
    }

    public function getPublicdir()
    {
        return $this->publicdir;
    }

    public function setFile(File $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
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

    public function isOwnedBy(\User\Entity\User $user)
    {
        return $this->getUser() === $user;
    }
}
