<?php

namespace App\Entity;

use App\Repository\VideoGameRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameRepository::class)]
class VideoGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'videoGames')]
    private ?Category $Category = null;

    #[ORM\ManyToOne(inversedBy: 'videoGames')]
    private ?Editor $Editor = null;

    #[ORM\ManyToONe(inversedBy: 'VideoGames')]
    private ?Image $converImage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $releaseDate): static
    {
        $this->releaseDate = new DateTime($releaseDate);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): static
    {
        $this->Category = $Category;

        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->Editor;
    }

    public function setEditor(?Editor $Editor): static
    {
        $this->Editor = $Editor;

        return $this;
    }

    public function getImage(): Image
    {
        return $this->converImage;
    }

    public function setImage(?Image $image): static
    {
        $this->converImage = $image;
        return $this;
    }
}
