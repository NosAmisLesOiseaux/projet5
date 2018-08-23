<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CaptureRepository")
 */
class Capture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $content;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull()
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull()
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(
     *     choices = { "draft", "published", "validated", "waiting for validation" }
     * )
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $created_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="capture")
     */
    private $comments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $naturalist_comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bird", inversedBy="captures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bird;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="captures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     */
    private $validated_by;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Image()
     */
    private $image;

    public function __construct()
    {
        $this->created_date = new \DateTime('now');
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

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
            $comment->setCapture($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCapture() === $this) {
                $comment->setCapture(null);
            }
        }

        return $this;
    }

    public function getNaturalistComment(): ?string
    {
        return $this->naturalist_comment;
    }

    public function setNaturalistComment(?string $naturalist_comment): self
    {
        $this->naturalist_comment = $naturalist_comment;

        return $this;
    }

    public function getBird(): ?Bird
    {
        return $this->bird;
    }

    public function setBird(?Bird $bird): self
    {
        $this->bird = $bird;

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

    public function getValidatedBy(): ?User
    {
        return $this->validated_by;
    }

    public function setValidatedBy(?User $validated_by): self
    {
        $this->validated_by = $validated_by;

        return $this;
    }

    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}
