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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reign;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phylum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class;

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

    public function __construct()
    {
        $this->captures = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReign(): ?string
    {
        return $this->reign;
    }

    public function setReign(string $reign): self
    {
        $this->reign = $reign;

        return $this;
    }

    public function getPhylum(): ?string
    {
        return $this->phylum;
    }

    public function setPhylum(string $phylum): self
    {
        $this->phylum = $phylum;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getBirdOrder(): ?string
    {
        return $this->bird_order;
    }

    public function setBirdOrder(string $bird_order): self
    {
        $this->bird_order = $bird_order;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getVernacularname(): ?string
    {
        return $this->vernacularname;
    }

    public function setVernacularname(string $vernacularname): self
    {
        $this->vernacularname = $vernacularname;

        return $this;
    }

    public function getValidname(): ?string
    {
        return $this->validname;
    }

    public function setValidname(string $validname): self
    {
        $this->validname = $validname;

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

    /**
     * @return Collection|Capture[]
     */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    public function addCapture(Capture $capture): self
    {
        $this->captures[] = $capture;
        
        $capture->setBird($this);

        return $this;
    }

    public function removeCapture(Capture $capture): self
    {
        $this->captures->removeElement($capture);
    }
}
