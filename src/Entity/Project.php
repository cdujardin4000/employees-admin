<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Table(name: 'projects')]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $project_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $emp_no = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?DateTime $created_at = null;

    #[ORM\Column]
    private ?DateTime $updated_at = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects',indexBy: 'project_id')]
    #[ORM\JoinTable(name: 'emp_projects')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'project_id')]
    #[ORM\InverseJoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $employees;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'project_managements')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private ?Employee $scrum = null;

    /**
     * @return Employee|null
     */
    public function getScrum(): ?Employee
    {
        return $this->scrum;
    }

    /**
     * @param Employee|null $scrum
     */
    public function setScrum(?Employee $scrum): void
    {
        $this->scrum = $scrum;
    }

    public function __toString() :string {
        return $this->description;
    }

    #[Pure] public function __construct()
    {
        $this->employees = new ArrayCollection();
    }


    public function getProjectId(): ?int
    {
        return $this->project_id;
    }

    public function setProjectId(int $project_id): self
    {
        $this->project_id = $project_id;

        return $this;
    }

    public function getEmpNo(): ?int
    {
        return $this->emp_no;
    }

    public function setEmpNo(?int $emp_no): self
    {
        $this->emp_no = $emp_no;

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

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }


    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        $this->employees->removeElement($employee);

        return $this;
    }
}
