<?php

namespace App\Entity;

use App\Repository\EmpTitleRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: EmpTitleRepository::class)]
class EmpTitle
{
    #[Id, Column(type: 'string'), ManyToOne(targetEntity: Employee::class, inversedBy: 'titles')]
    private Employee $employee;
    #[Id, Column(type: 'string'), ManyToOne(targetEntity: Title::class, inversedBy: 'attributions')]
    private Title $titleNo;
    #[Id, Column(type: Types::DATE_MUTABLE)]
    private ?DateTime $fromDate;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'attributions')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $titleOwning;



    public function __construct(
        Employee $employee,
        Title $titleNo,
        DateTime $fromDate,

    )
    {
        $this->titleOwnings = new ArrayCollection;
        $this->employee = $employee;
        $this->titleNo = $titleNo;
        $this->fromDate = $fromDate;
    }


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $toDate = null;



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

    public function getFromDate(): ?DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

}
