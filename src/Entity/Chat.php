<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 * @ApiResource(
 *     denormalizationContext={"groups"={"chat:write"}},
 *     normalizationContext={"groups"={"readChat"}},
 *     collectionOperations={
 *      "get"={
 *     "path"="/users/promo/{idp}/apprenant/{ida}/chats"
 *     },
 *     "chatGeneral"={
 *     "method"="post",
 *     "path"="/api/promo/{idp}/apprenant/{ida}/chats",
 *     "route_name"="chatGeneral",
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
 *     }
 *     },
 * )
 *

 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *  @Assert\NotBlank(message="ecrivez un message")
     * @Groups ({"chat:write","readChat"})
     */
    private $message;

    /**
     * @ORM\Column(type="blob", nullable=true)
     *  @Groups ({"chat:write","readChat"})
     */
    private $pieceJointes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     *  @Assert\NotBlank(message="ajouter le user")
     *  @Groups ({"chat:write"})
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="chats")
     *  @Assert\NotBlank(message="ajouter le promo")
     *  @Groups ({"chat:write"})
     */
    private $promo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPieceJointes()
    {
        return (string) $this->pieceJointes;
    }

    public function setPieceJointes($pieceJointes): self
    {
        $this->pieceJointes = $pieceJointes;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

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
}
