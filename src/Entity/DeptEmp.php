<?php

namespace App\Entity;

use App\Repository\DeptEmpRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DeptEmp
{
    #[ORM\Column]
    #[ORM\Id]
    private ?int $emp_no = null;

    #[ORM\Column(length: 4)]
    #[ORM\Id]
    private ?string $dept_no = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTime $from_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTime $to_date = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'affectations')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private $employee = null;
/**
    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'mutations')]
    #[ORM\JoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    private $department = null;
**/
    public function getEmpNo(): ?int
    {
        return $this->emp_no;
    }

    public function setEmpNo(int $emp_no)
    {
        $this->emp_no = $emp_no;

        return $this;
    }

    public function getDeptNo(): ?string
    {
        return $this->dept_no;
    }

    public function setDeptNo(string $dept_no): self
    {
        $this->dept_no = $dept_no;

        return $this;
    }

    public function getFromDate(): ?DateTime
    {
        return $this->from_date;
    }

    public function setFromDate(DateTime $from_date): self
    {
        $this->from_date = $from_date;

        return $this;
    }

    public function getToDate(): ?DateTime
    {
        return $this->to_date;
    }

    public function setToDate(DateTime $to_date): self
    {
        $this->to_date = $to_date;

        return $this;
    }

    public function __toString() :string {
        return (string)$this->dept_no;
    }

}
