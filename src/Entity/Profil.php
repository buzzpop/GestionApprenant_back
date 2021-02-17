<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le libelle ajouté existe déja"
 * )
 * @ApiResource(
 *     routePrefix="/admin",
 *      subresourceOperations={
 *          "get_users_by_profil"={
 *          "method"="GET",
 *          "path"="/profils/{id}/users",
 *          },
 *      },
 *     attributes={
 * "security"="is_granted('ROLE_Administrateur')",
 * "security_message"="Ressource accessible que par l'Admin",
 * },
 *     collectionOperations={
 *     "get_profils"={
 *      "method"="get",
 *     "path"="/profils",
 *      "normalization_context"={"groups"={"get_profils"}}
 *     },
 *      "post_profil"={"path"="/profils",
 *      "method"="post",
 *        "denormalization_context"={"groups"={"user:write"}},
 *      },
 *     },
 *      itemOperations={
 *     "get"={"path"="/profils/{id}"},
 *     "put"={"path"="/profils/{id}"},
 *     "delete"={"path"="/profils/{id}"},
 *
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archivage"})
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"get_profils","user:write","get_profil_by_id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="libelle obligatoire")
     * @Groups ({"get_profils","get_profil_by_id","user:write"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource()
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage=false;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }
}
