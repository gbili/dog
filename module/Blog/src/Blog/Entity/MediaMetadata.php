<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_metadatas")
 */
class MediaMetadata 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Media", inversedBy="metadatas")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $media;

    /**
     * @ORM\Column(name="locale", type="string", length=64)
     */
    private $locale;

    /**
     * Title
     * @ORM\Column(name="alt", type="string", length=64)
     */
    private $alt;

    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function setMedia(Media $media=null)
    {
        $this->media = $media;
    }

    public function getMedia()
    {
        return $this->media;
    }

    public function hasMedia()
    {
        return null !== $this->media;
    }

    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    public function getAlt()
    {
        return $this->alt;
    }

    public function setDescription($description = null)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
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
}
