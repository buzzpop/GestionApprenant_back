<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
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
 * message="la valeur existe déjà"
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
 *
 *
 *     },
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
 *          },
 *
 *     },
 *     itemOperations={
 *      "post_user"={
 *      "method"="GET",
 *     "path"="/users/{id}",
 *     },
 *
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"statut"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="email obligatoire")
     * @Groups ({"get_profil_by_id"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups ({"get_profil_by_id"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id"})
     *
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id"})
     *
     */
    private $lastname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut=false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id"})
     *
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_profil_by_id"})
     *
     */
    private $tel;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups ({"get_profil_by_id"})
     * @Assert\NotNull
     *
     */
    private $profil;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups ({"get_profil_by_id"})
     *
     */
    private $avatar;

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
        return (string) $this->password;
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
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

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
        return stream_get_contents($this->avatar) ;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
