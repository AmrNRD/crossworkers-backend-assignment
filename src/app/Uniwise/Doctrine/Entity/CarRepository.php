<?php
namespace Uniwise\Doctrine\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class CarRepository extends ServiceEntityRepository {

    /**
     * Allowed Fields To Be Filtered.
     *
     * @var array
     */
    protected $allowedFiltersExact = [
        'id',
    ];

    /**
     * Allowed Fields To Be Filtered [partial word].
     *
     * @var array
     */
    protected $allowedFilters = [
        'model',
        'brand',
        'color',
        'mile_per_gallon'
    ];

    /**
     * Allowed Relation To Be Filtered.
     *
     * @var array
     */
    protected $allowedRelationFilters = [
        'equipment'=>'filterEquipments',
    ];


    /**
     * Allowed sort types.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'model',
        'brand',
        'color',
        'mile_per_gallon'
    ];

    /*
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager) {
        parent::__construct($registry, Car::class);
        $this->entityManager=$entityManager;
    }

    /**
     * @param array $parms
     * @return array|Car[]
     */
    public function getAll(array $parms=[]) {
        $filters=array_key_exists('filter',$parms)?$parms['filter']:null;
        $sortBy=array_key_exists('sortBy',$parms)?$parms['sortBy']:null;
        $sort=array_key_exists('sort',$parms)?$parms['sort']:null;
        $q = $this->createQueryBuilder('car');
        if($filters){
            $index = 0;
            foreach ($filters as $key => $value) {
                if(in_array($key,$this->allowedFilters)){
                    $index > 0
                        ? $q->andWhere("car.${key} LIKE '%${value}%'")
                        : $q->where("car.${key} LIKE '%${value}%'");
                    $index++;
                }else if(in_array($key,$this->allowedFiltersExact)){
                    $index > 0
                        ? $q->andWhere("car.${key} = '${value}'")
                        : $q->where("car.${key} = '${value}'");
                    $index++;
                }else if(array_key_exists($key,$this->allowedRelationFilters)){
                    $methodToCall=$this->allowedRelationFilters[$key];
                    $q=$this->$methodToCall($q,$value,$index>0);
                    $index++;
                }

            }
        }
        $q->orderBy('car.'.(in_array($sortBy,$this->allowedSorts)?$sortBy:"id"), (in_array($sort,['ASC','DESC'])?$sort:"ASC"));


        return $q->getQuery()->execute();
    }

    public function filterEquipments(QueryBuilder $queryBuilder,string $values,bool $set_and){

        $queryBuilder->join('car.carEquipments', 'e');
        if($set_and){
            $queryBuilder->andWhere("e.name LIKE '%$values%'");
        }
        else{
            $queryBuilder->where("e.name LIKE '%$values%'");
        }

        return $queryBuilder;
    }


    /**
     * @param $data
     * @return Car
     */
    public function store($data): Car
    {
        $car = new Car();
        $car->setBrand($data['brand']);
        $car->setModel($data['model']);
        $car->setColor($data['color']);
        $car->setMPG($data['mile_per_gallon']);

        $this->entityManager->persist($car);
        $this->entityManager->flush();

        return $car;
    }


    /**
     * @param Car $car
     * @param array $data
     * @return Car
     */
    public function update(Car $car, array $data): Car
    {
        $car->setBrand($data['brand']);
        $car->setModel($data['model']);
        $car->setColor($data['color']);
        $car->setMPG($data['mile_per_gallon']);

        $this->entityManager->persist($car);
        $this->entityManager->flush();

        return $car;
    }


    /**
     * @param Car $car
     * @param Equipment $equipment
     * @return Car
     */
    public function addEquipment(Car $car,Equipment $equipment): Car
    {
        $car->addEquipment($equipment);

        $this->entityManager->persist($car);
        $this->entityManager->flush();
        return $car;
    }

    /**
     * @param Car $car
     * @param Equipment $equipment
     * @return Car
     */
    public function removeEquipment(Car $car,Equipment $equipment): Car
    {
        $car->removeEquipment($equipment);

        $this->entityManager->persist($car);
        $this->entityManager->flush();
        return $car;
    }


    /**
     * @param Car $car
     * @return Car
     */
    public function delete(Car $car): Car
    {
        $this->entityManager->remove($car);
        $this->entityManager->flush();
        return true;
    }

}