<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="medias")
 *
 * @ORM\Entity(repositoryClass="Blog\Entity\Repository\Media")
 */
class Media implements \User\IsOwnedByInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="medias")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="slug", type="string", length=64)
     */
    private $slug;

    /**
     * @ORM\Column(name="publicdir", type="string", length=64)
     */
    private $publicdir;

    /**
     * The media is linked to this post
     * @ORM\OneToMany(targetEntity="PostData", mappedBy="media", cascade={"persist"})
     */
    private $posts;

    /**
     * The media is linked to this profile 
     * @ORM\OneToMany(targetEntity="\User\Entity\Profile", mappedBy="media", cascade={"persist"})
     */
    private $profiles;

    /**
     * The media is linked to these dogs 
     * @ORM\OneToMany(targetEntity="\Dogtore\Entity\Dog", mappedBy="media", cascade={"persist"})
     */
    private $dogs;

    /**
     * The media is linked to this post
     * @ORM\OneToMany(targetEntity="MediaMetadata", mappedBy="media", cascade={"persist"})
     */
    private $metadatas;

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
        $this->metadatas = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setUser(\User\Entity\User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUri()
    {
        return $this->getFile()->getUri();
    }

    public function getType()
    {
        return $this->getFile()->getType();
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

    public function __call($method, $params)
    {
        $allowed = array('Dog', 'Metadata', 'Profile', 'Post');
        $allowedPlural = array('Dogs', 'Metadatas', 'Profiles', 'Posts');

        $parts = preg_split('/(?=[A-Z])/', $method);
        $uCFirstWhat = array_pop($parts);
        $what = strtolower($uCFirstWhat);

        $isSingle = in_array($uCFirstWhat, $allowed);
        $isPlural = in_array($uCFirstWhat, $allowedPlural);

        if (!$isSingle && !$isPlural) {
            throw new \Exception("$uCFirstWhat not implemented.");
        }

        array_push($parts, (($isSingle)? 'Thing':'Things'));
        $genericMethod = implode('', $parts);

        return call_user_func(array($this, $genericMethod), (($isSingle)? $what . 's' : $what), current($params));
    }

    public function getThing($what)
    {
        return $this->$what;
    }

    public function getThings($what)
    {
        return $this->$what;
    }

    public function addThing($what, $thing)
    {
        $thing->setMedia($this);
        $this->$what->add($thing);
    }

    public function addThings($what, \Doctrine\Common\Collections\Collection $things)
    {
        foreach ($things as $thing) {
            $this->addThing($what, $thing);
        }
    }

    public function removeThing($what, $thing)
    {
        $this->$what->removeElement($thing);
        $thing->setMedia(null);
    }

    public function removeThings($what, \Doctrine\Common\Collections\Collection $things)
    {
        foreach ($things as $thing) {
            $this->removeThing($what, $thing);
        }
    }

    public function removeAllThings($what)
    {
        $this->removeThings($what, $this->getThings($what));
        return $this;
    }

    /**
     * Heavy: n^2
     * TODO find a better solution for uniqueness of localized metadata
     */
    public function addMetadata(MediaMetadata $metadata)
    {
        if ($metadata->hasMedia()) {
            throw new \Exception((($metadata->getMedia() === $this)?'This metadata already has a different media' : 'addMetadata will set the media so dont set it yourself'));
        }

        if (!$metadata->hasLocale()) {
            throw new \Exception('Metadata needs to have a locale');
        }

        if ($this->hasLocalizedMetadata($metadata->getLocale())) {
            throw new \Exception('Only one metadata per locale');
        }

        $metadata->setMedia($this);
        $this->metadatas->add($metadata);
    }

    public function addMetadatas(\Doctrine\Common\Collections\Collection $metadatas)
    {
        foreach ($metadatas as $metadata) {
            $this->addMetadata($metadata);
        }
    }

    public function removeAllMetadatas()
    {
        $this->removeMetadatas($this->getMetadatas());
    }

    public function removeMetadatas(\Doctrine\Common\Collections\Collection $metadatas)
    {
        foreach ($metadatas as $metadata) {
            $this->removeMetadata($metadata);
        }
    }

    public function getMetadatas()
    {
        return $this->metadatas;
    }

    public function getLocalizedMetadata($locale)
    {
        foreach ($this->metadatas->toArray() as $existingMeta)  {
            if ($existingMeta->getLocale() === $locale) {
                return $existingMeta;
            }
        }
        return null;
    }

    public function hasLocalizedMetadata($locale)
    {
        return null !== $this->getLocalizedMetadata($locale);
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
        return $this->getPublicdir() . '/' . $this->getFile()->getBasename();
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
