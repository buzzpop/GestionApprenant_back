<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="La competence existe deja"
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 *
 * @ApiResource (
 *     attributes={
 *     "normalization_context"={"groups"={"compt:read"}},
 *     "denormalization_context"={"groups"={"ajoutC:write"}},
 *     },
 *     routePrefix="/admin",
 *     collectionOperations={
 *       "get"={
 *      "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_Cm'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource"
 *     },
 *     "post_competence"={
 *     "method"="POST",
 *     "path"="/competences",
 *      "access_control"="(is_granted('ROLE_Administrateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource"
 *     },
 *
 *     },
 *     itemOperations={
 *          "get"={
 *      "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_Cm'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource"
 *     },
 *          "put_competence"={
 *            "method"="PUT",
 *     "path"="/api/admin/competences/{id}",
 *      "route_name"="put_competence",
 *      "access_control"="(is_granted('ROLE_Administrateur'))",
 *       "access_control_message"="Vous n'avez pas access à cette Ressource"
 *     },
 *     "delete"={
 *      "access_control"="(is_granted('ROLE_Administrateur'))",
 *       "access_control_message"="Vous n'avez pas access à cette Ressource"
 *     }
 *     }
 *
 * )
 */
class Competences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"addC:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ajouter le nom de la competence")
     * @Groups ({"compt:read","grpandC:read","comp_in_g:read","ajoutC:write","addC:write","cAndG:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"compt:read","grpandC:read","comp_in_g:read","ajoutC:write","addC:write","cAndG:read"})
     * @Assert\NotBlank(message="Ajouter le descriptif de la competence")
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived=false;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="competences",cascade={"persist"})
     * @Assert\NotBlank(message="Affecter la competence à un groupe")
     * @Assert\Count (
     *     min="1",
     *     minMessage="Ajouter au minimum un groupe de competence à la competence",
     * )
     * @Groups ({"ajoutC:write"})
     */
    private $groupeCompetence;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competences",cascade={"persist"})
     * @Assert\NotBlank(message="Ajouter les niveaux de competences")
     * @Groups ({"compt:read","ajoutC:write"})
     * @Assert\Count (
     *     min="3",
     *     max="3",
     *     minMessage="Ajouter 3 Niveaux",
     *     maxMessage="Ajouter 3 Niveaux"
     * )
     */
    private $niveaux;

    public function __construct()
    {
        $this->groupeCompetence = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
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
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetence(): Collection
    {
        return $this->groupeCompetence;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetence->contains($groupeCompetence)) {
            $this->groupeCompetence[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        $this->groupeCompetence->removeElement($groupeCompetence);

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetences($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetences() === $this) {
                $niveau->setCompetences(null);
            }
        }

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }
}
