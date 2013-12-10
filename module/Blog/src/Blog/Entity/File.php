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
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;

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

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
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

    public function addMedia(Media $media)
    {
        $media->setFile($this);
        $this->medias->add($media);
    }

    public function addMedias(\Doctrine\Common\Collections\Collection $medias)
    {
        foreach ($medias as $media) {
            $this->addMedia($media);
        }
    }

    public function removeMedias(\Doctrine\Common\Collections\Collection $medias)
    {
        foreach ($medias as $media) {
            $this->removeMedia($media);
        }
    }

    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);
        $media->setFile(null);
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

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'tmp_name') {
                $key = 'uri';
            }
            $method = 'set' . ucfirst($key);
            $this->$method($value);
        }
    }

    public function delete()
    {
        exec('rm ' . $this->getUri());
        return !file_exists($this->getUri());
    }
}
