<?php

namespace App\Entity;

use App\Repository\SalaryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name:"salaries")]
#[ORM\UniqueConstraint(name: "emp_no_from_date_unique",
    columns: ["emp_no", "from_date"])]
#[ORM\Entity(repositoryClass: SalaryRepository::class)]
class Salary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $emp_no = null;

    #[ORM\Column]
    private ?int $salary = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $from_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $to_date = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'salaries')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private ?Employee $employee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmpNo(): ?int
    {
        return $this->emp_no;
    }

    public function setEmpNo(int $emp_no): self
    {
        $this->emp_no = $emp_no;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->from_date;
    }

    public function setFromDate(\DateTimeInterface $from_date): self
    {
        $this->from_date = $from_date;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->to_date;
    }

    public function setToDate(\DateTimeInterface $to_date): self
    {
        $this->to_date = $to_date;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
