<?php

namespace App\Entity;

use App\Repository\EmpTitleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpTitleRepository::class)]
class EmpTitle
{
    #[ORM\Column]
    #[ORM\Id]
    private ?int $empNo = null;

    #[ORM\Column(length: 4)]
    #[ORM\Id]
    private ?int $titleNo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fromDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $toDate = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'titles')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private  $employee = null;

    public function getId(): ?int
    {
        return $this->empNo;
    }

    public function getEmpNo(): ?int
    {
        return $this->empNo;
    }

    public function setEmpNo(int $empNo): self
    {
        $this->empNo = $empNo;

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

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(\DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

}
