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
     * The name it had in the client's machine
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(name="basename", type="string", length=64)
     */
    private $basename;

    /**
     * The containing directory path
     * @ORM\Column(name="dirpath", type="string", length=64)
     */
    private $dirpath;

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

    public function setBasename($basename)
    {
        $this->basename = $basename;
    }

    public function getBasename()
    {
        return $this->basename;
    }

    public function setDirpath($dirpath)
    {
        $this->dirpath = $dirpath;
    }

    public function getDirpath()
    {
        return $this->dirpath;
    }

    public function setUri($uri)
    {
        $parts = explode('/', $uri);
        $basename = array_pop($parts);
        $dirpath = implode('/', $parts);
        $this->setBasename($basename);
        $this->setDirpath($dirpath);
    }

    public function getUri()
    {
        return $this->getDirpath() . '/' . $this->getBasename();
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

    public function getSrc()
    {
        $dirparts = explode('/', $this->getDirpath());
        return '/' . end($dirparts) . '/' . $this->getBasename();
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

    public function hydrateWithFormData(array $data)
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

    public function move($newBasename)
    {
        if (0 ===  preg_match('#^[a-zA-Z0-9][a-zA-Z0-9-_]*?\\.[a-zA-Z0-9]+$#', $newBasename)) {
            throw new \Exception('The filename is not supported');
        }
        $newUri = $this->getDirpath() . '/' . $newBasename;

        $count = 0;
        while (file_exists($newUri)) {
            if (!isset($basenameNoSuffix)) {
                $basenameParts = explode('.', $newBasename);
                $basenameSuffix = array_pop($basenameParts);
                $basenameNoSuffix = implode('.', $basenameParts);
            }
            $newUri = $this->getDirpath() . '/' . $basenameNoSuffix . '-' . ++$count . '.' . $basenameSuffix;
        }

        exec("mv {$this->getUri()} $newUri");
        if (!file_exists($newUri) || file_exists($this->getUri())) {
            return false;
        }
        $this->setUri($newUri);
        $this->setName($this->getBasename());
        return true;
    }
}
