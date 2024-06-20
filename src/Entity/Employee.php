<?php
namespace App\Entity;

use App\Repository\EmployeeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\Table('employees')]
class Employee  implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name: 'emp_no', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 14)]
    private ?string $first_name = null;

    #[ORM\Column(length: 16)]
    private ?string $last_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $birth_date = null;

    #[ORM\Column(length: 1)]
    #[Assert\Choice(choices:['M', 'F', 'X'])]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(name: 'avatar_url', length: 255, nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email()]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $hire_date = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created_at = null;

    #[ORM\Column]
    public ?string $ago;
    #[ORM\Column]
    public ?string $current;

    public ?string $currentTitle;



    #[ORM\ManyToMany(targetEntity: Department::class, inversedBy:'employees', fetch: "EAGER")]
    #[ORM\JoinTable(name: 'dept_emp')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    #[ORM\InverseJoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    private Collection $departments;

    #[ORM\ManyToMany(targetEntity: Department::class, inversedBy:'managers', fetch: "EAGER")]
    #[ORM\JoinTable(name: 'dept_manager')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    #[ORM\InverseJoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    private Collection $managements;

    #[ORM\ManyToMany(targetEntity: Title::class, fetch: "EAGER")]
    #[ORM\JoinTable(name: 'emp_title')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    #[ORM\InverseJoinColumn(name: 'title_no', referencedColumnName: 'title_no')]
    private Collection $titles;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: DeptEmp::class,   fetch: "EAGER")]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    #[ORM\InverseJoinColumn(name: 'dept_name', referencedColumnName: 'dept_name')]
    private Collection $affectations;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Demand::class,   fetch: "EAGER")]
    private Collection $demands;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Salary::class,   fetch: "EAGER")]
    private Collection $salaries;

    #[ORM\OneToMany(mappedBy: 'titleOwning', targetEntity: EmpTitle::class)]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $attributions;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: DeptManager::class)]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $supervisions;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Mission::class)]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'id')]
    private Collection $missions;

    #[ORM\OneToMany(mappedBy: 'supervisor', targetEntity: Intern::class)]
    private Collection $interns;

    #[ORM\OneToMany(mappedBy: 'scrum', targetEntity: Project::class)]
    private Collection $project_managements;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'employees', indexBy: 'emp_no')]
    #[ORM\JoinTable(name: 'emp_projects')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'emp_no')]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Leave::class)]
    private Collection $leaves;

/*    #[ORM\ManyToMany(targetEntity: Car::class, inversedBy: 'employees', indexBy: 'emp_no')]
    #[ORM\JoinTable(name: 'cars_emp')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'emp_no')]
    #[ORM\InverseJoinColumn(name: 'car_id', referencedColumnName: 'car_id')]
    private Collection $cars;*/

    /**
     * #[ORM\OneToMany(mappedBy: 'employee', targetEntity: DeptManager::class)]
     * #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
     * private Collection $managingStories;
     *
     * #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Salary::class, orphanRemoval: false)]
     * private Collection $salaries;**@throws Exception
     */


    #[Pure] public function __construct()
    {
        $this->leaves = new ArrayCollection();
        $this->project_managements = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->interns = new ArrayCollection();
        $this->managements = new ArrayCollection();
        $this->supervisions = new ArrayCollection();
        $this->attributions = new ArrayCollection();
        $this->affectations = new ArrayCollection();
        $this->departments = new ArrayCollection();
        $this->demands = new ArrayCollection();
        $this->salaries = new ArrayCollection();
        $this->titles = new ArrayCollection();
        $this->missions = new ArrayCollection();
        //$this->cars = new ArrayCollection();

    }


    /**


    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: DeptManager::class)]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $managingStories;

    private function hashPassword($plaintextPassword) {
        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $this,
            $plaintextPassword
        );

        $entity->setPassword($hashedPassword);
        dd($entity);
        };
    } **/



    public function get_emp_no(): ?int
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        if (!$this->avatarUrl) {
            return null;
        }
        if (str_contains($this->avatarUrl, '/')) {
            return $this->avatarUrl;
        }
        return sprintf('/assets/img/employees/%s', $this->avatarUrl);
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getAvatar(): ?string
    {
        if (!$this->avatarUrl) {
            return null;
        }
        if (str_contains($this->avatarUrl, '/')) {
            return $this->avatarUrl;
        }
        return sprintf('/assets/img/employees/%s', $this->avatarUrl);
    }

    public function setAvatar(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHireDate(): ?DateTimeInterface
    {
        return $this->hire_date;
    }

    public function setHireDate(DateTimeInterface $hire_date): self
    {
        $this->hire_date = $hire_date;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    /**
     * @return Collection<int, DeptEmp>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function getCurrentAffectation(): DeptEmp
    {
        return $this->affectations->last();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';     //dump($roles);die;

        return array_unique($roles, SORT_STRING);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function __toString() :string {
        return "$this->first_name $this->last_name";
    }

    /**
     *
     *
     * @return Collection<int, Demand>
     */
    public function getDemands(): Collection
    {
        return $this->demands;
    }

    public function addDemand(Demand $demand): self
    {
        if (!$this->demands->contains($demand)) {
            $this->demands->add($demand);
            $demand->setEmployee($this);
        }

        return $this;
    }

    public function removeDemand(Demand $demand): self
    {
        // set the owning side to null (unless already changed)
        if ($this->demands->removeElement($demand) && $demand->getEmployee() === $this) {
            $demand->setEmployee(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Salary>
     */
    public function getSalaries(): Collection
    {
        return $this->salaries;
    }

    public function addSalary(Salary $salary): self
    {
        if (!$this->salaries->contains($salary)) {
            $this->salaries->add($salary);
            $salary->setEmployee($this);
        }

        return $this;
    }

    public function removeSalary(Salary $salary): self
    {
        if ($this->salaries->removeElement($salary)) {
            // set the owning side to null (unless already changed)
            if ($salary->getEmployee() === $this) {
                $salary->setEmployee(null);
            }
        }

        return $this;
    }

    public function getTitles(): Collection
    {
        return $this->titles;
    }

    public function getAttributions(): Collection
    {
        return $this->attributions;
    }

    public function getSupervisions(): Collection
    {
        return $this->supervisions;
    }

    public function getManagements(): Collection
    {
        return $this->managements;
    }

    public function setCurrent(Department $current) :self
    {
        $this->current = $current;

        return $this;
    }

    public function setCurrentTitle(Title $currentTitle) :self
    {
        $this->currentTitle = $currentTitle;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setEmployee($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        // set the owning side to null (unless already changed)
        if ($this->missions->removeElement($mission) && $mission->getEmployee() === $this) {
            $mission->setEmployee(null);
        }

        return $this;
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

    /**
     * @return Collection<int, Intern>
     */
    public function getInterns(): Collection
    {
        return $this->interns;
    }

    public function addIntern(Intern $intern): self
    {
        if (!$this->interns->contains($intern)) {
            $this->interns->add($intern);
            $intern->setSupervisor($this);
        }

        return $this;
    }

    public function removeIntern(Intern $intern): self
    {
        if ($this->interns->removeElement($intern)) {
            // set the owning side to null (unless already changed)
            if ($intern->getSupervisor() === $this) {
                $intern->setSupervisor(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Project>
     */
    public function getProjectManagements(): Collection
    {
        return $this->project_managements;
    }

    public function addProjectManagement(Project $projectManagement): self
    {
        if (!$this->project_managements->contains($projectManagement)) {
            $this->project_managements->add($projectManagement);
            $projectManagement->setScrum($this);
        }

        return $this;
    }

    public function removeProjectManagement(Project $projectManagement): self
    {
        if ($this->project_managements->removeElement($projectManagement)) {
            // set the owning side to null (unless already changed)
            if ($projectManagement->getScrum() === $this) {
                $projectManagement->setScrum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addEmployee($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeEmployee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Leave>
     */
    public function getLeaves(): Collection
    {
        return $this->leaves;
    }

    public function addLeave(Leave $leave): self
    {
        if (!$this->leaves->contains($leave)) {
            $this->leaves->add($leave);
            $leave->setEmployee($this);
        }

        return $this;
    }

    public function removeLeave(Leave $leave): self
    {
        if ($this->leaves->removeElement($leave)) {
            // set the owning side to null (unless already changed)
            if ($leave->getEmployee() === $this) {
                $leave->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Collection

    public function getCars(): ArrayCollection|Collection
    {
        return $this->cars;
    }*/

    /**
     * @param ArrayCollection|Collection $cars

    public function setCars(ArrayCollection|Collection $cars): void
    {
        $this->cars = $cars;
    }*/

}