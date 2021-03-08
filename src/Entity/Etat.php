<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="Etat", orphanRemoval=true)
     */
    private $no_etat;

    public function __construct()
    {
        $this->no_etat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getNoEtat(): Collection
    {
        return $this->no_etat;
    }

    public function addNoEtat(Sortie $noEtat): self
    {
        if (!$this->no_etat->contains($noEtat)) {
            $this->no_etat[] = $noEtat;
            $noEtat->setEtat($this);
        }

        return $this;
    }

    public function removeNoEtat(Sortie $noEtat): self
    {
        if ($this->no_etat->removeElement($noEtat)) {
            // set the owning side to null (unless already changed)
            if ($noEtat->getEtat() === $this) {
                $noEtat->setEtat(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
