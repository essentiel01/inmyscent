<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private $sex;

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
     * @ORM\Column(type="string", length=255)
     */
    private $topNotes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\FamilyNote", inversedBy="products")
     */
    private $familyNote;

    public function __construct()
    {
        $this->familyNote = new ArrayCollection();
    }

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

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

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

    /**
     * @return Collection|FamilyNote[]
     */
    public function getFamilyNote(): Collection
    {
        return $this->familyNote;
    }

    public function addFamilyNote(FamilyNote $familyNote): self
    {
        if (!$this->familyNote->contains($familyNote)) {
            $this->familyNote[] = $familyNote;
        }

        return $this;
    }

    public function removeFamilyNote(FamilyNote $familyNote): self
    {
        if ($this->familyNote->contains($familyNote)) {
            $this->familyNote->removeElement($familyNote);
        }

        return $this;
    }
}
