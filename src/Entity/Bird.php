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
        return $this->reign;
    }

    /**
     * @param string $reign
     * @return Bird
     */
    public function setReign(string $reign): self
    {
        $this->reign = $reign;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhylum(): ?string
    {
        return $this->phylum;
    }

    /**
     * @param string $phylum
     * @return Bird
     */
    public function setPhylum(string $phylum): self
    {
        $this->phylum = $phylum;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return Bird
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
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
}
