<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le tag existe deja"
 * )
 * @ApiResource (
 *      attributes={
 *      "normalization_context"={"groups"={"tag:read"}}
 * },
 *     collectionOperations={
 *          "get_Tag"={
                "method"="GET",
 *              "path"="/admin/tags",
 *               "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *
 *     },
 *     itemOperations={

 *      "get_Tag_by_id"={
 *              "method"="GET",
 *              "path"="/admin/tags/{id}",
 *      "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *      "put_Tag"={
 *              "method"="PUT",
 *              "path"="/admin/tags/{id}",
 *      "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"tag:read"})
     * @Assert\NotBlank(message="Ajouter le nom du Tag")
     * @Groups ({"tags:read","tags_by_grpe"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"tag:read"})
     * @Assert\NotBlank(message="Ajouter la description du Tag")
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, inversedBy="tags",cascade={"persist"})
     * @Groups ({"tag:read"})
     */
    private $groupeTag;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived=false;

    public function __construct()
    {
        $this->groupeTag = new ArrayCollection();
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

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTag(): Collection
    {
        return $this->groupeTag;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTag->contains($groupeTag)) {
            $this->groupeTag[] = $groupeTag;
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        $this->groupeTag->removeElement($groupeTag);

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
