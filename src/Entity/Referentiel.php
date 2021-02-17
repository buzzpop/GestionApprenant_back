<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le nom du referentiel doit etre unique"
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 *
 * @ApiResource (
 *     routePrefix="/admin",
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"ref"}},
 *     },
 *       "getComp"={
 *     "method"="get",
 *     "path"="api/admin/referentiels/{idR}/grpecompetences/{idG}",
 *     "route_name"="getComp",
 *      "normalization_context"={"groups"={"competences"}},
 *     },
 *
 *     "getcompAndGroup"={
 *             "method"="GET",
 *     "path"="/referentiels/grpecompetences",
 *        "normalization_context"={"groups"={"cAndG:read"}},
 *     },
 *     "postRef"={
 *       "method"="post",
 *     "path"="api/admin/referentiels",
 *      "route_name"="postRef",
 *      "denormalization_context"={"groups"={"addGC:write"}},
 *      "deserialize"=false,
 *             "swagger_context"={
 *                 "consumes"={
 *                     "multipart/form-data",
 *                 },
 *                 "parameters"={
 *                     {
 *                         "in"="formData",
 *                         "name"="file",
 *                         "type"="file",
 *                         "description"="The file to upload",
 *                     },
 *                 },
 *            },
 *          },
 *      },
 *     itemOperations={
 *     "delete",
 *     "get"={
 *        "normalization_context"={"groups"={"GdeC:read"}},
 *     },
 *
 *      "putRef"={
 *      "method"="PUT",
 *     "path"="/referentiels/{id}",
 *     "route_name"="putRef",
 *      "deserialize"=false,
 *             "swagger_context"={
 *                 "consumes"={
 *                     "multipart/form-data",
 *                 },
 *                 "parameters"={
 *                     {
 *                         "in"="formData",
 *                         "name"="file",
 *                         "type"="file",
 *                         "description"="The file to upload",
 *                     },
 *                 },
 *             },
 *          },
 *     }
 *
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"cAndG:read","ref"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir le nom du referentiel")
     * @Groups ({"addGC:write","getP_R_A_F:read","cAndG:read","ref"})
     *
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez renseigner la presentation")
     * @Groups ({"addGC:write","getP_R_A_F:read","cAndG:read","ref"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob")
     * @Assert\NotBlank(message="Veuillezrenseigner le programme")
     * @Groups ({"addGC:write","getP_R_A_F:read","cAndG:read","ref"})
     */
    private $programme;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez saisir les criteres d'admission")
     *  @Groups ({"addGC:write","getP_R_A_F:read","cAndG:read","ref"})
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="text", length=255)
     * @Assert\NotBlank(message="Veuillez saisir les criteres d'evaluation")
     *  @Groups ({"addGC:write","getP_R_A_F:read","cAndG:read","ref"})
     */
    private $critereEvaluation;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="referentiels")
     * @Groups ({"cAndG:read","addGC:write","GdeC:read","ref","competences"})
     * @Assert\Count (
     *     min="1",
     *     minMessage="Ajouter au moins un groupe de competence"
     * )
     */
    private $groupeCompetences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived=false;

    public function __construct()
    {
        $this->promos = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return  $this->id;
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme()
    {
        return base64_encode(stream_get_contents($this->programme) ) ;
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

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
