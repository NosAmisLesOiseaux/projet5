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
     *     choices = { "draft", "published", "validated", "waiting_for_validation" }
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
     * @ORM\OneToOne(targetEntity="App\Entity\Image", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="captures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $validated_by;

    public function __construct()
    {
        $this->created_date = new \DateTime('now');
        $this->comments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Capture
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Capture
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Capture
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Capture
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @param string|null $complement
     * @return Capture
     */
    public function setComplement(string $complement = null): self
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return Capture
     */
    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     * @return Capture
     */
    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return Capture
     */
    public function setCity(string $city = null): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param null|string $status
     * @return Capture
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    /**
     * @param \DateTimeInterface $created_date
     * @return Capture
     */
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

    /**
     * @param Comment $comment
     * @return Capture
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCapture($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return Capture
     */
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

    /**
     * @return null|string
     */
    public function getNaturalistComment(): ?string
    {
        return $this->naturalist_comment;
    }

    /**
     * @param null|string $naturalist_comment
     * @return Capture
     */
    public function setNaturalistComment(?string $naturalist_comment): self
    {
        $this->naturalist_comment = $naturalist_comment;

        return $this;
    }

    /**
     * @return Bird|null
     */
    public function getBird(): ?Bird
    {
        return $this->bird;
    }

    /**
     * @param Bird|null $bird
     * @return Capture
     */
    public function setBird(?Bird $bird): self
    {
        $this->bird = $bird;

        return $this;
    }

    /**
     * @param Image|null $image
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return $this
     */
    public function removeImage()
    {
        $this->image = null;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Capture
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getValidatedBy(): ?User
    {
        return $this->validated_by;
    }

    /**
     * @param User|null $validated_by
     * @return Capture
     */
    public function setValidatedBy(?User $validated_by): self
    {
        $this->validated_by = $validated_by;

        return $this;
    }
}
