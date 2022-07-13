<?php

namespace App\Entity;

use App\Repository\MoviesRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MoviesRepository::class)
 */
class Movies
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Exclude()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank
     */
    private $casts = [];

    /**
     * @ORM\Column(type="date")
     * @JMS\Type("DateTime<'d-m-Y'>")
     * @Assert\NotBlank
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $director;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank
     */
    private $ratings = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JMS\Exclude()
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCasts(): ?array
    {
        return $this->casts;
    }

    public function setCasts(array $casts): self
    {
        $this->casts = $casts;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getRatings(): ?array
    {
        return $this->ratings;
    }

    public function setRatings(array $ratings): self
    {
        $this->ratings = $ratings;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
