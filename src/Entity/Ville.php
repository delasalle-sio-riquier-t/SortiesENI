<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="idVille", orphanRemoval=true)
     */
    private $idVille;

    public function __construct()
    {
        $this->idVille = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection|Lieu[]
     */
    public function getIdVille(): Collection
    {
        return $this->idVille;
    }

    public function addIdVille(Lieu $idVille): self
    {
        if (!$this->idVille->contains($idVille)) {
            $this->idVille[] = $idVille;
            $idVille->setIdVille($this);
        }

        return $this;
    }

    public function removeIdVille(Lieu $idVille): self
    {
        if ($this->idVille->removeElement($idVille)) {
            // set the owning side to null (unless already changed)
            if ($idVille->getIdVille() === $this) {
                $idVille->setIdVille(null);
            }
        }

        return $this;
    }
}
