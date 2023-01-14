<?php

namespace App\Entity;

use App\Repository\DptTitleRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table('dept_title')]
#[ORM\Entity(repositoryClass: DptTitleRepository::class)]
class DptTitle
{

    #[ORM\Column('dept_no', type: 'string')]
    #[ORM\Id]
    private ?string $deptNo = null;

    #[ORM\Column('dept_title', type: 'string')]
    #[ORM\Id]
    private ?int $titleNo = null;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
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

    public function getTitleNo(): ?int
    {
        return $this->titleNo;
    }

    public function setTitleNo(int $titleNo): self
    {
        $this->titleNo = $titleNo;

        return $this;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }
}
