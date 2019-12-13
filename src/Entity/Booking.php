<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="La date d'arrivée doit être au bon format !")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit être ulteriéure a la date d'aujourd'hui !", groups={"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="La date de départt doit être au bon format !")
     * @Assert\GreaterThan(propertyPath="startDate", message="La date départ doit être plus eloignée que la date d'arrivée !")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;
    /** 
     * Callback est applé a chaque fois qu'on fait une réservation
     *@ORM\PrePersist
     * 
     * 
     */
      
     //Gestion des dates 
     public function prePrepersist()
     {
         // Si date est vide, on prend la date a l'instant
         if(empty($this->createAt)){
             $this->createAt= new \DateTime();

            }

          if(empty($this->amount)){
            //Prix de l'annonce*nombre de jour 
                  $this->amount=$this->ad->getPrice() * $this->getDuration();
            }
      }
       
       //Prise en compte de la disponibilité de l'annonce 
      public function isBookableDates(){

       // 1 ) Il faut connaites  les dates qui sont impossibles pour l'annonce
          $notAvailableDays=$this->ad->getNotAvailableDays();
       // 2 ) Il faut comparer les dates choisies et les dates impossibles 
            $bookingDays=$this->getDays();
             
            $formatDays=function($days){
                return $days->format('Y-m-d');
            };

            //Tableu des chaines de caractéres de mes journées
            $days=            array_map($formatDays, $bookingDays);
            $notAvailable=    array_map($formatDays,  $notAvailableDays);


            //Ici je vais boucler sur chacune de jounée qui concerne notre réservation
            foreach ($days as $day) {
               //Recherche d'information, si le jour se trouve dans le tableau 
               if(array_search($day, $notAvailable)!==false) return false;

            }
            return true ;
      }
       /**
        * Permet de récuperer un tableau des journées qui correspondent à ma reservation
        * 
        * @return array un tableau d'objet DateTime répresentant les jours  de la réservation 
        */
      public function getDays(){
             
        $resultat=range($this->startDate->getTimestamp(),
                         $this->endDate->getTimestamp(),
                         24*60*60
      );
        
      $days=array_map(function($dayTimestamp){

        return new \DateTime(date('Y-m-d', $dayTimestamp));
       }, $resultat);
          
        return $days;
      }

      public function getDuration(){
     //Faire la difference entre deux dates et envoie un objet DateInterval
     $diff=$this->endDate->diff($this->startDate);
        //Retourne le nombre de jour réserver 
         return $diff->days;
      }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
