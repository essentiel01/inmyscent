<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $topNotes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $familyNotes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $baseNotes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $heartNotes;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getTopNotes(): ?string
    {
        return $this->topNotes;
    }

    public function setTopNotes(string $topNotes): self
    {
        $this->topNotes = $topNotes;

        return $this;
    }

   
    public function getFamilyNotes(): ?string
    {
        return $this->familyNotes;
    }

    public function setFamilyNotes(string $familyNotes): self
    {
        $this->familyNotes = $familyNotes;

        return $this;
    }

    public function getBaseNotes(): ?string
    {
        return $this->baseNotes;
    }

    public function setBaseNotes(?string $baseNotes): self
    {
        $this->baseNotes = $baseNotes;

        return $this;
    }

    public function getHeartNotes(): ?string
    {
        return $this->heartNotes;
    }

    public function setHeartNotes(?string $heartNotes): self
    {
        $this->heartNotes = $heartNotes;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
