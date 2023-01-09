<?php

namespace App\Entity;

use App\Repository\EmpTitleRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: EmpTitleRepository::class)]
class EmpTitle
{
    #[Id, Column(type: 'string'), ManyToOne(targetEntity: Employee::class, inversedBy: 'titles')]
    private Employee $empNo;
    #[Id, Column(type: 'string'), ManyToOne(targetEntity: Title::class, inversedBy: 'attributions')]
    private Title $titleNo;
    #[Id, Column(type: Types::DATE_MUTABLE)]
    private ?DateTime $fromDate;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'attributions')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $titleOwning;



    #[Pure] public function __construct(
        Employee $empNo,
        Title $titleNo,
        DateTime $fromDate,

    )
    {
        $this->titleOwnings = new ArrayCollection;
        $this->empNo = $empNo;
        $this->titleNo = $titleNo;
        $this->fromDate = $fromDate;
    }


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTime $toDate = null;



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

    public function getFromDate(): ?DateTime
    {
        return $this->fromDate;
    }

    public function setFromDate(DateTime $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?DateTime
    {
        return $this->toDate;
    }

    public function setToDate(DateTime $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }



}
