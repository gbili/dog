<?php
namespace Dogtore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="dogs")
 * @ORM\Entity(repositoryClass="Dogtore\Entity\Repository\Dog")
 */
class Dog
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="locale", type="string", length=6)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false, unique=false)
     */
    private $name;

    /**
     * Bidirectional - OWNING
     *
     * One dog has one user, One user has Many dogs
     * Owner
     *
     * @ORM\ManyToOne(targetEntity="\GbiliUserModule\Entity\UserDataInterface", inversedBy="dogs")
     * @ORM\JoinColumn(name="userdata_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $userdata;

    /**
     * @var string
     *
     * @ORM\Column(name="breed", type="string", length=64, nullable=true, unique=false)
     */
    private $breed;

    /**
     * #FFDD33 -> FFDD33
     * Hex color without #
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=6, nullable=true, unique=false)
     */
    private $color;

    /**
     * m    | f
     * Male | Female
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true, unique=false)
     */
    private $gender;

    /**
     * @var float 
     *
     * @ORM\Column(name="weightkg", type="float", length=64, nullable=true, unique=false)
     */
    private $weightkg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * Bidirectional - INVERSE
     *
     * One dog has Many medias - One Media has One Dog
     *             ^-----^                      ^---^
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\Blog\Entity\Media", mappedBy="dog", cascade={"persist"})
     */
    private $medias;

    /**
     * Unidirectional - OWNING 
     *
     * One dog has One profilemedia - One porfilemedia has One Dog
     *
     * @ORM\ManyToOne(targetEntity="\Blog\Entity\Media", inversedBy="dogs")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $media;

    /**
     * Tell us why you chose this dog. Is it because of the breed?
     * On the contrary, is it because of a crazy story? Tell us more.
     *
     * @ORM\Column(name="whythisdog", type="text", nullable=true)
     */
    private $whythisdog;

    /**
     *
     */
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
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUser(\GbiliUserModule\Entity\UserInterface $user)
    {
        $this->setUserData($user->getData());
        return $this;
    }

    public function hasUser()
    {
        return $this->hasUserData();
    }

    public function getUser()
    {
        return $this->getUserData()->getUser();
    }

    public function setUserData(\GbiliUserModule\Entity\UserDataInterface $userdata)
    {
        $this->userdata = $userdata;
        return $this;
    }

    public function hasUserData()
    {
        return $this->userdata !== null;
    }

    public function getUserData()
    {
        return $this->userdata;
    }

    public function setWhythisdog($whythisdog = null)
    {
        $this->whythisdog = $whythisdog;
        return $this;
    }

    public function getWhythisdog()
    {
        return $this->whythisdog;
    }

    public function setBreed($breed = null)
    {
        $this->breed = $breed;
        return $this;
    }

    public function getBreed()
    {
        return $this->breed;
    }

    public function setColor($color = null)
    {
        $this->color = $color;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setGender($gender = null)
    {
        $this->gender = $gender;
        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setWeightkg($weightkg = null)
    {
        $this->weightkg = $weightkg;
        return $this;
    }

    public function getWeightkg()
    {
        return $this->weightkg;
    }

    public function setMedia(\Blog\Entity\Media $media = null)
    {
        $this->media = $media;
    }

    public function getMedia()
    {
        return $this->media;
    }

    public function hasMedia()
    {
        return $this->media instanceof \Blog\Entity\Media;
    }

    public function __call($method, $params)
    {
        $allowed = array('Media');
        $allowedPlural = array('Medias');

        $parts = preg_split('/(?=[A-Z])/', $method);
        $uCFirstWhat = array_pop($parts);
        $what = strtolower($uCFirstWhat);

        $isSingle = in_array($uCFirstWhat, $allowed);
        $isPlural = in_array($uCFirstWhat, $allowedPlural);

        if (!$isSingle && !$isPlural) {
            throw new \Exception('Not implemented');
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

    public function hasthings($what)
    {
        return !$this->$what->isEmpty();
    }

    public function addThing($what, $thing)
    {
        $thing->setDog($this);
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
        $thing->setDog(null);
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
     * @ORM\PrePersist
     */
    public function setBirthdate(\DateTime $time)
    {
        $this->birthdate = $time;
    }

    /**
     * Get Created Date
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
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
