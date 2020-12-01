<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetencesRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le Groupe de competence existe deja"
 * )
 * @ApiResource(
 *      normalizationContext={"groups"={"grpandC:read"}},
 *     denormalizationContext={"groups"={"addC:write"}},
 *     routePrefix="/admin",
 *          collectionOperations={
 *          "get",
 *          "competences_in_groupe"={
 *          "method"="GET",
 *          "path"="/groupe_competences/competences",
 *     "normalization_context"={"groups"={"comp_in_g:read"}},
 *     },
 *     "post",
 *     },
 *     itemOperations={
 *     "get",
 *    "put_Groupe"={
 *     "method"="PUT",
 *     "path"="/grpcomp/{id}",
 *
 *     }
 * }
 * )
 */
class GroupeCompetences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="Ajouter le nom du groupe de competence")
     * @Groups ({"grpandC:read","addC:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ajouter le descriptif du groupe de competence")
     * @Groups ({"grpandC:read","addC:write"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived=false;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, mappedBy="groupeCompetence")
     * @Assert\Count(
     *     min="1",
     *     minMessage="ajouter une competence au minimum dans le groupe de competence"
     * )
     * @Groups ({"grpandC:read","comp_in_g:read","addC:write"})
     * @ApiSubresource()
     */
    private $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): self
    {
        $this->descriptif = $descriptif;

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

    /**
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if ($this->competences->removeElement($competence)) {
            $competence->removeGroupeCompetence($this);
        }

        return $this;
    }
}
