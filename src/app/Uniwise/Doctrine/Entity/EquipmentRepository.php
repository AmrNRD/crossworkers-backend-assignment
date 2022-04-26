<?php

namespace Uniwise\Doctrine\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class EquipmentRepository extends ServiceEntityRepository
{
    /*
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager) {
        parent::__construct($registry, Equipment::class);
        $this->entityManager=$entityManager;
    }


    /**
     * @return array|Equipment[]
     */
    public function getAll()
    {
        return $this->findAll();
    }


    public function store($data):Equipment
    {
        $equipment = new Equipment();
        $equipment->setName($data['name']);

        $this->entityManager->persist($equipment);
        $this->entityManager->flush();

        return $equipment;
    }

    /**
     * @param Equipment $equipment
     * @param array $data
     * @return Equipment
     */
    public function update(Equipment $equipment, array $data): Equipment
    {
        $equipment->setName($data['name']);

        $this->entityManager->persist($equipment);
        $this->entityManager->flush();


        return $equipment;
    }

    /**
     * @param Equipment $equipment
     * @return Equipment
     */
    public function delete(Equipment $equipment): Equipment
    {
        $this->entityManager->remove($equipment);
        $this->entityManager->flush();
        return true;
    }
}
