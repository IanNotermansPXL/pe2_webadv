<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;



#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private $roles = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'Firstname cannot be empty',
    )]
    #[Assert\Type(
        type: 'string',
        message: '{{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Firstname must be at least {{ limit }} long',
        maxMessage: 'Firstname must be shorter than {{ limit }}',
    )]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'Lastname cannot be empty',
    )]
    #[Assert\Type(
        type: 'string',
        message: '{{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Lastname must be at least {{ limit }} long',
        maxMessage: 'Lastname must be shorter than {{ limit }}',
    )]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'Password cannot be empty',
    )]
    #[Assert\Length(
        min: 6,
        max: 4096,
        minMessage: 'Password must be at least {{ limit }} characters long',
        maxMessage: 'Password must be shorter than {{ limit }} characters',
    )]
    private ?string $password_hash = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'Email cannot be empty',
    )]
    #[Assert\Email(
        message: 'The email "{{ value }}" is not a valid email.',
    )]
    private ?string $email = null;

    /**
     * @var Collection<int, Loan>
     */
    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'user')]
    private Collection $loans;

    public function __construct()
    {
        $this->loans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): static
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(Loan $loan): static
    {
        if (!$this->loans->contains($loan)) {
            $this->loans->add($loan);
            $loan->setUser($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): static
    {
        if ($this->loans->removeElement($loan)) {
            // set the owning side to null (unless already changed)
            if ($loan->getUser() === $this) {
                $loan->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password_hash;
    }

    public function getSalt(): ?string
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function setPlainPassword(string $plainPassword, UserPasswordHasherInterface $passwordHasher): static
    {
        $this->password_hash = $passwordHasher->hashPassword($this, $plainPassword);

        return $this;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return $this->first_name . ' ' . $this->last_name;
    }
}
