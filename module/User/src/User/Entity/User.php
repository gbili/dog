<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User 
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
     * @ORM\OneToMany(targetEntity="\Blog\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\Blog\Entity\Media", mappedBy="user")
     */
    private $medias;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="\Dogtore\Entity\Dog", mappedBy="owner")
     */
    private $dogs;

    /**
     * @var string
     *
     * @ORM\Column(name="uniquename", type="string", length=64, nullable=false, unique=true)
     */
    private $uniquename;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=64, nullable=false, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity="Profile", inversedBy="user")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false, unique=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=64, nullable=false, unique=false)
     */
    private $role;

    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dogs  = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function isAdmin()
    {
        return $this->getRole() === 'admin';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setUniquename($uniquename)
    {
        $this->uniquename = $uniquename;
    }

    public function getUniquename()
    {
        return $this->uniquename;
    }

    public function setProfile(ProfileInterface $profile)
    {
        $this->profile = $profile;
    }

    public function getProfile()
    {
        if (null === $this->profile) {
            $this->profile = new Profile(); 
        }
        return $this->profile;
    }

    public function hasProfile()
    {
        return $this->profile instanceof ProfileInterface;
    }

    public function setPassword($clearPassword)
    {
        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $this->password = $bcrypt->create($clearPassword);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isThisPassword($unknownClearPassword)
    {
        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        return $bcrypt->verify($unknownClearPassword, $this->getPassword());
    }

    public function hydrate($data)
    {
        foreach ($data as $method => $param) {
            if ('id' === $method) continue;
            $method = 'set' . ucfirst($method);
            $this->$method($param);
        }
    }

    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add post
     * @param \Blog\Entity\Post $posts
     */
    public function addPost(\Blog\Entity\Post $post)
    {
        $post->setCategory($this);
        $this->posts->add($post);
    }

    /**
     * Add post
     * @param \Doctrine\Common\Collections\Collection $posts
     */
    public function addPosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->addPost($post);
        }
    }

    /**
     * Remove posts
     * @param \Blog\Entity\Post $posts
     */
    public function removePost(\Blog\Entity\Post $post)
    {
        $this->posts->removeElement($post);
        $post->setUser(null);
    }

    /**
     * Remove posts
     * @param \Blog\Entity\Post $posts
     */
    public function removePosts(\Doctrine\Common\Collections\Collection $posts)
    {
        foreach ($posts as $post) {
            $this->removePost($post);
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDogs()
    {
        return $this->dogs;
    }

    /**
     * @return boolean 
     */
    public function hasDogs()
    {
        return !$this->dogs->isEmpty();
    }

    /**
     * Add dog
     * @param \Dogtore\Entity\Dog $dogs
     */
    public function addDog(\Dogtore\Entity\Dog $dog)
    {
        if (!$dog->hasOwner()) {
            $dog->setOwner($this);
        }
        $this->dogs->add($dog);
    }

    /**
     * Add dog
     * @param \Doctrine\Common\Collections\Collection $dogs
     */
    public function addDogs(\Doctrine\Common\Collections\Collection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->addDog($dog);
        }
    }

    /**
     * Remove dogs
     * @param \Dogtore\Entity\Dog $dogs
     */
    public function removeDog(\Dogtore\Entity\Dog $dog)
    {
        $this->dogs->removeElement($dog);
        $dog->setOwner(null);
    }

    /**
     * Remove dogs
     * @param \Dogtore\Entity\Dog $dogs
     */
    public function removeDogs(\Doctrine\Common\Collections\Collection $dogs)
    {
        foreach ($dogs as $dog) {
            $this->removeDog($dog);
        }
    }
}
