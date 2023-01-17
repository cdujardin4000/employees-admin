<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
#[ORM\Table('departments')]
class Department
{
    #[ORM\Id]
    //#[ORM\None]
    #[ORM\Column('dept_no', type: 'string')]
    private ?string $dept_no = null;

    #[ORM\Column(length: 40)]
    private ?string $dept_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dept_img = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $roi_url = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, mappedBy: 'departments')]
    #[ORM\JoinTable(name: 'dept_emp')]
    #[ORM\JoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    #[ORM\InverseJoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $employees;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: DeptEmp::class,   fetch: "EAGER")]
    #[ORM\JoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    #[ORM\InverseJoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $mutations;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: DptTitle::class)]
    #[ORM\JoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    private  Collection $offers;

    #[ORM\ManyToMany(targetEntity: Employee::class, mappedBy: 'managements')]
    #[ORM\JoinTable(name: 'dept_manager')]
    #[ORM\JoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    #[ORM\InverseJoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $managers;

    #[Pure] public function __construct()
    {
        $this->managers = new ArrayCollection();
        $this->employees = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->mutations = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->dept_no;
    }

    public function getDeptNo(): ?string
    {
        return $this->dept_no;
    }

    public function setDeptNo($deptNo): self
    {
        $this->dept_no = $deptNo;

        return $this;
    }

    public function getDeptName(): ?string
    {
        return $this->dept_name;
    }

    public function setDeptName(string $dept_name): self
    {
        $this->dept_name = $dept_name;

        return $this;
    }

    public function getOffers(): ?collection
    {
        return $this->offers;
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

    /**
     * @return string|null
     */
    public function getDeptImg(): ?string
    {
        return $this->dept_img;
    }

    /**
     * @param string|null $dept_img
     */
    public function setDeptImg(?string $dept_img): self
    {
        $this->dept_img = $dept_img;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRoiUrl(): ?string
    {
        return $this->roi_url;
    }

    public function setRoiUrl(?string $roi_url): self
    {
        $this->roi_url = $roi_url;

        return $this;
    }

    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(Employee $manager): self
    {
        if (!$this->managers->contains($manager)) {
            $this->managers->add($manager);
        }

        return $this;
    }

    public function removeManager(Employee $manager): self
    {
        $this->managers->removeElement($manager);

        return $this;
    }

    public function getMutations(): Collection
    {
        return $this->mutations;
    }

    public function __toString() :string {
        return (string)$this->dept_name;
    }
}
