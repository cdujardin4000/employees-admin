<?php

namespace App\Entity;

use App\Repository\InternRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'interns')]
#[ORM\Entity(repositoryClass: InternRepository::class)]
class Intern
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $emp = null;

    #[ORM\Column(length: 150)]
    private ?string $fullname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $end_date = null;

    #[ORM\Column(length: 150)]
    private ?string $dept = null;

    #[ORM\ManyToOne(targetEntity: Employee::Class, inversedBy: 'interns')]
    #[ORM\JoinColumn(name: 'emp', referencedColumnName: 'emp_no')]
    private ?Employee $supervisor;

    #[ORM\ManyToOne(targetEntity: Department::Class, inversedBy: 'interns')]
    #[ORM\JoinColumn(name: 'dept', referencedColumnName: 'dept_no', nullable: false)]
    private ?Department $department = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getEmp(): ?int
    {
        return $this->emp;
    }

    /**
     * @param int|null $emp
     * @return Intern
     */
    public function setEmpNo(?int $emp): self
    {
        $this->emp = $emp;

        return $this;
    }

    public function getSupervisor(): ?Employee
    {
        return $this->supervisor;
    }

    public function setSupervisor(Employee $supervisor): self
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDept(): ?string
    {
        return $this->dept;
    }

    /**
     * @param string|null $dept
     */
    public function setDept(?string $dept): void
    {
        $this->dept = $dept;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }
}
