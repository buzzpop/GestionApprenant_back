<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="user_type", type="string")
 * @ORM\DiscriminatorMap({"formateur"="Formateur","apprenant"="Apprenant","cm"="Cm","user"="User"})
 *
 * @UniqueEntity(
 * fields={"email"},
 * message="l'email existe déjà"
 * )
 *
 * @ApiResource(
 *      routePrefix="/admin",
 *      attributes={
 * "security"="is_granted('ROLE_Administrateur')",
 * "security_message"="Ressource accessible que par l'Admin",
 * "normalization_context"={"groups"={"get_profil_by_id"}}
 * },
 *      collectionOperations={
 *     "get_users"={
 *      "method"="GET",
 *     "path"="/users",
 *     },
 *
 *    "add_user"={
 * "method"="POST",
 * "path"="/api/admin/users" ,
 * "route_name"="add_user",
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
 *          }
 *
 *     },
 *     itemOperations={
 *      "get_user"={
 *      "method"="GET",
 *     "path"="/users/{id}",
 *     },
 *
 *      "put_user"={
 *      "method"="PUT",
 *     "path"="/users/{id}",
 *     "route_name"="put_user",
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
 *     },
 *
 *      "archive_user"={
 *      "method"="DELETE",
 *     "path"="/users/{id}",
 *     },
 *
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isArchived"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"groupe:write","get_profil_by_id"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="email obligatoire")
     * @Groups ({"get_profil_by_id","apprenant:read","getP_R_A_F:read","getApp:read"})
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     * @Groups ({"get_profil_by_id"})
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="mot de passe obligatoire")
     *   * @Groups ({"get_profil_by_id"})
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id","apprenant:read","getP_R_A_F:read","getApp:read"})
     * @Assert\NotBlank(message="renseigner votre prénom")
     *
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id","apprenant:read","getP_R_A_F:read","getApp:read"})
     * @Assert\NotBlank(message="renseigner votre nom")
     *
     */
    protected $lastname;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isArchived=false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id","apprenant:read","getP_R_A_F:read","getApp:read"})
     * @Assert\NotBlank(message="renseigner votre adresse")
     *
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id","apprenant:read","getP_R_A_F:read","getApp:read"})
     * @Assert\NotBlank(message="renseigner votre numero de téléphone")
     *
     */
    protected $tel;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"get_profil_by_id","apprenant:read","getP_R_A_F:read","getApp:read"})
     *
     */
    protected $profil;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups ({"get_profil_by_id","apprenant:read","getP_R_A_F:read"})
     *
     */
    protected $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="users")
     */
    private $chats;

    public function __construct()
    {

        $this->chats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)  $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return  ucfirst( $this->firstname);
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return ucfirst($this->lastname);
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

      public function getAvatar()
        {
            if ($this->avatar){
                return base64_encode((stream_get_contents($this->avatar)));
            }
          return null;
        }


    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setUsers($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getUsers() === $this) {
                $chat->setUsers(null);
            }
        }

        return $this;
    }
}
