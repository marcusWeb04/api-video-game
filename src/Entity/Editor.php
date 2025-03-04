<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EditorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EditorRepository::class)]
class Editor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le champs de dois pas être inferrieur à {{limit}} caractere',
        maxMessage: 'Le champs de dois pas être suppieur à {{limit}} caractere',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le champs de dois pas être inferrieur à {{limit}} caractere',
        maxMessage: 'Le champs de dois pas être suppieur à {{limit}} caractere',
    )]
    private ?string $country = null;

    /**
     * @var Collection<int, VideoGame>
     */
    #[ORM\OneToMany(targetEntity: VideoGame::class, mappedBy: 'Editor')]
    private Collection $videoGames;

    public function __construct()
    {
        $this->videoGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, VideoGame>
     */
    public function getVideoGames(): Collection
    {
        return $this->videoGames;
    }

    public function addVideoGame(VideoGame $videoGame): static
    {
        if (!$this->videoGames->contains($videoGame)) {
            $this->videoGames->add($videoGame);
            $videoGame->setEditor($this);
        }

        return $this;
    }

    public function removeVideoGame(VideoGame $videoGame): static
    {
        if ($this->videoGames->removeElement($videoGame)) {
            // set the owning side to null (unless already changed)
            if ($videoGame->getEditor() === $this) {
                $videoGame->setEditor(null);
            }
        }

        return $this;
    }
}
