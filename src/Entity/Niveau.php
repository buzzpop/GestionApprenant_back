<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 *
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ajouter le libelle niveau")
     * @Groups ({"compt:read","ajoutC:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez saisir les criteres d'evaluation")
     * @Groups ({"compt:read","ajoutC:write"})
     */
    private $critere_evaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ajouter le groupe d'action")
     * @Groups ({"compt:read","ajoutC:write"})
     */
    private $groupe_action;

    /**
     * @ORM\ManyToOne(targetEntity=Competences::class, inversedBy="niveaux")
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived=false;

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

    public function getCritereEvaluation(): ?string
    {
        return $this->critere_evaluation;
    }

    public function setCritereEvaluation(string $critere_evaluation): self
    {
        $this->critere_evaluation = $critere_evaluation;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->groupe_action;
    }

    public function setGroupeAction(string $groupe_action): self
    {
        $this->groupe_action = $groupe_action;

        return $this;
    }

    public function getCompetences(): ?Competences
    {
        return $this->competences;
    }

    public function setCompetences(?Competences $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }
}
