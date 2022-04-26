<?php
namespace Uniwise\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CarRepository")
 * @ORM\Table(name="car")
 */
class Car {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    private $brand;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    private $model;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    private $color;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     * @var int
     */
    private $miles_per_gallon;

    /**
     * @ORM\ManyToMany(targetEntity="Equipment")
     */
    private $carEquipment;

    public function __construct()
    {
        $this->carEquipment = new ArrayCollection();
    }

    /**
     * @param string $brand
     * @return $this
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param int $capacity
     * @return $this
     */
    public function setMPG(int $miles_per_gallon): self
    {
        $this->miles_per_gallon = $miles_per_gallon;

        return $this;
    }

    /**
     * @param Equipment $equipment
     */
    public function addEquipment(Equipment $equipment)
    {
        if ($this->carEquipment->contains($equipment)) {
            return;
        }

        $this->carEquipment[] = $equipment;
    }

    /**
     * @param Equipment $equipment
     */
    public function removeEquipment(Equipment $equipment)
    {
        if (!$this->carEquipment->contains($equipment)) {
            return;
        }

        $this->carEquipment->removeElement($equipment);
    }
}