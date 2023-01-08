<?php

namespace App\Entity;

use App\Repository\TitleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TitleRepository::class)]
#[ORM\Table('titles')]
class Title
{

    #[ORM\Id]
    #[ORM\Column]
    private ?int $title_no = null;

    #[ORM\Column(length: 55)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;


    public function getTitleNo(): ?int
    {
        return $this->title_no;
    }

    public function setTitleNo(int $title_no): self
    {
        $this->title_no = $title_no;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
