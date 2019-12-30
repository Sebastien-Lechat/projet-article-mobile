<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner votre prenom")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank( message=" Veuillez renseigner votre nom !")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email( message=" Veuillez renseigner un email valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url( message="Veuillez donner une Url valide pour votre Avatar !")
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Flux", mappedBy="user")
     */
    private $userf;

    // Permet d'eviter la concatenation
    public function getFullName(){

        return "{$this->firstname}{$this->lastname}";
     }

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->userf = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }
    
    public function getUsername(){

    }
    public function getSalt(){
        
    }
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Article $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setUser($this);
        }

        return $this;
    }

    public function removeUser(Article $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getUser() === $this) {
                $user->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Flux[]
     */
    public function getUserf(): Collection
    {
        return $this->userf;
    }

    public function addUserf(Flux $userf): self
    {
        if (!$this->userf->contains($userf)) {
            $this->userf[] = $userf;
            $userf->setUser($this);
        }

        return $this;
    }

    public function removeUserf(Flux $userf): self
    {
        if ($this->userf->contains($userf)) {
            $this->userf->removeElement($userf);
            // set the owning side to null (unless already changed)
            if ($userf->getUser() === $this) {
                $userf->setUser(null);
            }
        }

        return $this;
    }
}
