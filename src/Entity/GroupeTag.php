<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le groupe de Tag existe deja"
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 * @ApiResource (
 *     routePrefix="/admin",
 *     attributes={
 *     "normalization_context"={"groups"={"tags:read"}}
 * },
 *
 *     collectionOperations={
 *           "get_groupeTag"={
 *      "method"="GET",
 *     "path"="/grptags",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      "normalization_context"={"groups"={"tags_by_grpe"}}
 *
 *     },
 *
 *       "post_groupeTag"={
 *      "method"="POST",
 *     "path"="/api/admin/grptags",
 *      "route_name"="post_groupeTag",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *     },
 *
 *     },
 *     itemOperations={
 *      "get_groupeTag_by_id"={
 *      "method"="GET",
 *     "path"="/grptags/{id}",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *     },
 *      "get_tags_by_groupeTag"={
 *      "method"="GET",
 *     "path"="/grptags/{id}/tags",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *     },
 *       "put_grptag"={
 *      "method"="PUT",
 *     "path"="api/admin/grptags/{id}",
 *     "route_name"="put_grptag",
 *     "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *
 *     },
 *
 *     }
 * )
 */
class GroupeTag
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
     * @Assert\NotBlank(message="Ajouter le nom du groupe de Tag")
     * @Groups ({"tags:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="groupeTag")
     * @Groups ({"tags:read","tags_by_grpe"})
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isArchived=false;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addGroupeTag($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeGroupeTag($this);
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
