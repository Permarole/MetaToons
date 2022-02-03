<?php

namespace App\Entity;

use App\Repository\WatchListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WatchListRepository::class)
 */
class WatchList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Manga::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $manga;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lastRead;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManga(): ?Manga
    {
        return $this->manga;
    }

    public function setManga(?Manga $manga): self
    {
        $this->manga = $manga;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLastRead(): ?int
    {
        return $this->lastRead;
    }

    public function setLastRead(?int $lastRead): self
    {
        $this->lastRead = $lastRead;

        return $this;
    }
}
