<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(
 * fields={"libelle"},
 * message="le libelle existe déjà"
 * )
 *
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 *
 * @ApiResource (
 *     routePrefix="/admin",
 *   collectionOperations={
 *     "get"={"normalization_context"={"groups"={"profilSortie:read"}},
 *       "access_control"="(is_granted('ROLE_Administrateur'))",
 *       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *     "post"={
 *       "access_control"="(is_granted('ROLE_Administrateur'))",
 *       "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     }
 *     },
 *     itemOperations={
 *       "get_profilSortie"={
 *      "method"="GET",
 *     "path"="/profil_sorties/{id}",
 *     },
 *       "put_profilSortie"={
 *      "method"="PUT",
 *     "path"="/profil_sorties/{id}",
 *     },
 *
 *      "archive_profil_sortie"={
 *      "method"="DELETE",
 *     "path"="/profil_sorties/{id}",
 *     },
 *
 *     }
 *     )

 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profilSortie:read"})
     */
    private $id;

    /**
     * @Assert\NotBlank(message="libelle obligatoire")
     * @ORM\Column(type="string", length=255)
     * @Groups({"profilSortie:read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     */
    private $apprenant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived=false;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
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
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

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
