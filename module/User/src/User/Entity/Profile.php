<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="profiles")
 * @ORM\Entity
 */
class Profile 
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=64, nullable=false, unique=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=64, nullable=true, unique=false)
     */
    private $surname;

    /**
     *
     * @ORM\OneToOne(targetEntity="User", mappedBy="profile")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="\Blog\Entity\Media", inversedBy="posts")
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

    /**
     *
     */
    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function getSurname()
    {
        return $this->surname;
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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
