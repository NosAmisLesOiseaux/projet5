<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BirdRepository")
 */
class Bird
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    const BIRD_REIGN = "Animalia";

    const BIRD_PHYLUM = "Chordata";

    const BIRD_CLASS = "Aves";

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bird_order;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $family;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vernacularname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $validname;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Capture", mappedBy="bird", orphanRemoval=true)
     */
    private $captures;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $cd_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_thumbnail;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getReign(): ?string
    {
        return self::BIRD_REIGN;
    }

    /**
     * @return null|string
     */
    public function getPhylum(): ?string
    {
        return self::BIRD_PHYLUM;
    }

    /**
     * @return null|string
     */
    public function getClass(): ?string
    {
        return self::BIRD_CLASS;
    }

    /**
     * @return null|string
     */
    public function getBirdOrder(): ?string
    {
        return $this->bird_order;
    }

    /**
     * @param string $bird_order
     * @return Bird
     */
    public function setBirdOrder(string $bird_order): self
    {
        $this->bird_order = $bird_order;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFamily(): ?string
    {
        return $this->family;
    }

    /**
     * @param string $family
     * @return Bird
     */
    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getVernacularname(): ?string
    {
        return $this->vernacularname;
    }

    /**
     * @param string $vernacularname
     * @return Bird
     */
    public function setVernacularname(string $vernacularname): self
    {
        $this->vernacularname = $vernacularname;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getValidname(): ?string
    {
        return $this->validname;
    }

    /**
     * @param string $validname
     * @return Bird
     */
    public function setValidname(string $validname): self
    {
        $this->validname = $validname;

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
     * @return Collection|Capture[]
     */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    /**
     * @param Capture $capture
     * @return Bird
     */
    public function addCapture(Capture $capture): self
    {
        $this->captures[] = $capture;
        
        $capture->setBird($this);

        return $this;
    }

    /**
     * @param Capture $capture
     * @return Bird
     */
    public function removeCapture(Capture $capture): self
    {
        $this->captures->removeElement($capture);
    }

    public function getCdName(): ?string
    {
        return $this->cd_name;
    }

    public function setCdName(string $cd_name): self
    {
        $this->cd_name = $cd_name;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getImageThumbnail(): ?string
    {
        return $this->image_thumbnail;
    }

    public function setImageThumbnail(?string $image_thumbnail): self
    {
        $this->image_thumbnail = $image_thumbnail;

        return $this;
    }
}
