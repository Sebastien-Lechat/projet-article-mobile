<?php

namespace App\Entity;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * cycle de vie 
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *   message=" Une autre annonce possede dèja ce titre, merci de le modifier"
 * )
 */
class Ad
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
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\length(min=20, minMessage=" Votre introduction doit faire plus de 20 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\length(min=100, minMessage="Votre Description doit faire plus de 100 caractères")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="ad")
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="ad", orphanRemoval=true)
     */
    private $comments;

    

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }
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
           $this->slug=$slugify->slugify($this->title);
         }
    }
         /**
          * Permet de récuperer le commentaire d'un auteur par apport a une annonce 
          * @param User  $author
          * @return Comment|null
          */
        public function getCommentFromAuthor(User $author ){

           //Ici,  je vais boucler mes commentaitres 

             foreach($this->comments as $comment ){
                 // Si le l'auteur du commentaire c'est le même que l'auteur ici, afficher le commentaire 
                 if($comment->getAuthor() === $author ) return $comment;
             }
                // sinon il retoure  nul
             return null;
        }
          /**
           * Permet d'otenir la mayenne globale des notes  pour cette annonce 
           * @return float 
           */
        public function getAvgRatings(){
            //Calculer la somme des notations
             $sum=array_reduce($this->comments->toArray(), function($total, $comment){
                 
                return $total + $comment->getRating();
             }, 0);

            //Faire la division pour avoir la moyenne
             if(count($this->comments) > 0 ) return  $sum / count($this->comments);

             return 0;
        }


       /**
        * Permet d'otenir un tableau des jours qui ne sont pas disponible pour cette annonce 
        *
        * @return array Un tableau DateTime représentant les jours d'occupation
        */
    public function getNotAvailableDays(){
        //Declaration d'une variable qui vas contenir les jours ou l'appartement n'est pas disponible
        $notAvailableDays=[];
          //on boucle ici sur chacune de réservation 
        foreach($this->bookings as $booking){
            //Calculer les jours qui se trouve entre la date d'arrivée et date de départ 
            $resultat=range($booking->getStartDate()->getTimestamp(),
                            $booking->getEndDate()->getTimestamp(),
                            24*60*60
        );
           //Transformer le tableau en une veritable objet datetime 
         $days=array_map(function($dayTimestamp){

                    return new \DateTime(date('y-m-d', $dayTimestamp));
          }, $resultat);
           
           $notAvailableDays=array_merge($notAvailableDays, $days);
        }
        
        return $notAvailableDays;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

        return $this;
    }

}
