<?php

namespace Uniwise\Symfony\Bundle\ApiBundle\Controller\Car;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Uniwise\Doctrine\Entity\Car;
use Uniwise\Doctrine\Entity\CarRepository;
use Uniwise\Doctrine\Entity\Equipment;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations as Rest;
/**
 * @Route("/car")
 */
class CarController extends FOSRestController {

    /*
     * @var CarRepository
     */
    protected $carRepository;

    public function __construct(CarRepository $carRepository){
        $this->carRepository=$carRepository;
    }


    /**
     * @Get("")
     * @param Request $request
     * @return View
     */
    public function index(Request $request):View
    {
        $filters = $request->query->all();

        return $this->view(['cars' => $this->carRepository->getAll($filters)]);
    }


    /**
     * @Get("/{id}")
     * @param Car $car
     * @return View
     * @throws NotFoundHttpException
     */
    public function show(Car $car): View
    {
        return $this->view(['car' => $car]);
    }

    /**
     * @Post("")
     * @param Request $request
     * @return View
     */
    public function store(Request $request): View
    {
        $car=$this->carRepository->store($request->request->all());
        return $this->view(['car' => $car], Response::HTTP_CREATED);
    }

    /**
     * @Put("/{id}")
     * @param Car $car
     * @param Request $request
     * @return View
     */
    public function update(Car $car,Request $request): View
    {
        $car=$this->carRepository->update($car,$request->request->all());
        return $this->view(['car' => $car], Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Post("/{car}/equipment/{equipment}")
     * @param Car $car
     * @param Equipment $equipment
     * @return View
     */
    public function addEquipment(Car $car, Equipment $equipment): View
    {
        $car=$this->carRepository->addEquipment($car,$equipment);

        return $this->view(['car' => $car]);
    }

    /**
     * @Rest\Delete("/{car}/equipment/{equipment}")
     * @param Car $car
     * @param Equipment $equipment
     * @return View
     */
    public function removeEquipment(Car $car, Equipment $equipment): View
    {
        $car=$this->carRepository->removeEquipment($car,$equipment);

        return $this->view(['car' => $car]);
    }

    /**
     * @Rest\Delete("/{id}")
     * @param Car $car
     * @return View
     */
    public function destroy(Car $car): View
    {
        $deleted=$this->carRepository->delete($car);

        return $this->view($deleted?"Deleted successfully":"Could not be deleted", $deleted?Response::HTTP_ACCEPTED:Response::HTTP_NO_CONTENT);
    }
}