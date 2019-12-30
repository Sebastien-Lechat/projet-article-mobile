<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Votre titre  doit faire plus 10 caractères",
     *      maxMessage = "Votre titre doit faire plus de 255 caractères"
     * )
     */
    private $title;

    /**
    * @ORM\Column(type="string", length=255)
    * @Assert\Length(
    *      min = 10,
    *      max = 255,
    *      minMessage = "Votre titre  doit faire plus 10 caractères",
    *      maxMessage = "Votre titre doit faire plus de 255 caractères"
    * )
    */
    private $subtitle;

    /**
     * @ORM\Column(type="text")
     * @Assert\length(min=100, minMessage="Votre Description doit faire plus de 100 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_At;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_At;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user")
     */
    private $user;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $img;

    /**
     * @ORM\Column(type="text")
     */
    private $link;

    /**
   * Permet d'initialiser le slug 
   * @ORM\PrePersist
   * @ORM\PreUpdate
   * 
   * @return void 
   */
  public function initializedSlug(){

    //verification de slug c-a-d le moment de la creation, mise a jour

    if(empty($this->slug))
    {
        //Instanciation de slsug
        $slugify= new Slugify();
         //Creation  de slug
      $this->link=$slugify->slugify($this->title);
    }
}



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_At;
    }

    public function setCreatedAt(\DateTimeInterface $created_At): self
    {
        $this->created_At = $created_At;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_At;
    }

    public function setUpdateAt(\DateTimeInterface $update_At): self
    {
        $this->update_At = $update_At;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function __toString()
    {
        return $this->getUser();
    }
}

