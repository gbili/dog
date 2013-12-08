<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="files")
 */
class File 
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
    private $name;

    /**
     * @ORM\Column(name="tmp_name", type="string", length=255)
     */
    private $tmpName;

    /**
     * Title
     * @ORM\Column(name="type", type="string", length=64)
     */
    private $type;

    /**
     * Title
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * The file is linked to these medias 
     * @ORM\OneToMany(targetEntity="Media", mappedBy="file", cascade={"persist"})
     */
    private $medias;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTmpName($tmpName)
    {
        $this->tmpName = $tmpName;
    }

    public function getTmpName()
    {
        return $this->tmpName;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMedias()
    {
        return $this->medias;
    }

    public function addMedias(\Doctrine\Common\Collections\Collection $medias)
    {
        foreach ($medias as $media) {
            $this->addMedia($media);
        }
    }

    public function addMedia(Media $media)
    {
        $media->setFile($this);
        $this->medias->add($media);
    }

    public function removeMedias(\Doctrine\Common\Collections\Collection $medias)
    {
        foreach ($medias as $media) {
            $media->setFile(null);
            $this->medias->removeElement($media);
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
}
