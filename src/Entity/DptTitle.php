<?php

namespace App\Entity;

use App\Repository\DptTitleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DptTitleRepository::class)]
class DptTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    private ?string $deptNo = null;

    #[ORM\Column]
    private ?int $tileNo = null;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'offers')]
    private ?Department $department = null;

    public function getDeptNo(): ?string
    {
        return $this->deptNo;
    }

    public function setDeptNo(string $deptNo): self
    {
        $this->deptNo = $deptNo;

        return $this;
    }

    public function getTileNo(): ?int
    {
        return $this->tileNo;
    }

    public function setTileNo(int $tileNo): self
    {
        $this->tileNo = $tileNo;

        return $this;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }
}
