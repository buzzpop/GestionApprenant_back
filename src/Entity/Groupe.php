<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 *  * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 * @ApiResource (
 *     denormalizationContext={"groups"={"groupe:write"}},
 *     routePrefix="/admin",
 *     collectionOperations={
 *     "get"={
 *     "normalization_context"={"groups"={"getP_R_A_F:read"}}
 *     },
 *      "get"={
 *     "path"="/groupes/apprenants",
 *     "normalization_context"={"groups"={"getApp:read"}}
 *     },
 *     "post",
 *
 *     },
 *     itemOperations={
 *     "get",
 *     "put",
 *     "delete"
 *     }
 * )
 */
class Groupe
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"getP_R_A_F:read","getApp:read","groupe:write"})
     *  @Assert\NotBlank(message="Donner un nom au groupe")
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     *  @Groups ({"getP_R_A_F:read","getApp:read","groupe:write"})
     *
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean")
     *  @Groups ({"groupe:write"})
     */
    private $isArchived=false;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ({"getP_R_A_F:read","getApp:read","groupe:write"})
     *  @Assert\NotBlank(message="Donner le type du groupe")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     *  @Groups ({"getP_R_A_F:read"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     *  @Groups ({"getP_R_A_F:read","getApp:read","groupe:write"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     *  @Groups ({"getP_R_A_F:read","groupe:write"})
     */
    private $formateurs;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->dateCreation= new \DateTime();

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
    }
}
