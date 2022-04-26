<?php

namespace Uniwise\Symfony\Bundle\ApiBundle\Controller\Car;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Uniwise\Doctrine\Entity\Equipment;
use Uniwise\Doctrine\Entity\EquipmentRepository;

/**
 * @Route("/equipment")
 */
class EquipmentController extends FOSRestController
{

    /*
    * @var CarRepository
    */
    protected $equipmentRepository;

    public function __construct(EquipmentRepository $equipmentRepository){
        $this->equipmentRepository=$equipmentRepository;
    }

    /**
     * @Get("")
     * @param EquipmentRepository $equipmentRepository
     * @return View
     */
    public function index(): View
    {
        return $this->view(['equipments' => $this->equipmentRepository->getAll()]);
    }

    /**
     * @Get("/{id}")
     * @param Equipment $equipment
     * @return View
     * @throws NotFoundHttpException
     */
    public function show(Equipment $equipment): View
    {
        return $this->view(['equipment' => $equipment]);
    }

    /**
     * @Post("")
     * @param Request $request
     * @return View
     */
    public function store( Request $request): View
    {
        $equipment =$this->equipmentRepository->store($request->request->all());

        return $this->view(['equipment' => $equipment], Response::HTTP_CREATED);
    }

    /**
     * @Put("/{id}")
     * @param Equipment $equipment
     * @param Request $request
     * @return View
     */
    public function update(Equipment $equipment, Request $request): View
    {
        $car=$this->equipmentRepository->update($equipment,$request->request->all());
        return $this->view(['car' => $car], Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Delete("/{id}")
     * @param Equipment $equipment
     * @return View
     */
    public function destroy(Equipment $equipment): View
    {
        $deleted=$this->equipmentRepository->delete($equipment);

        return $this->view($deleted?"Deleted successfully":"Could not be deleted", $deleted?Response::HTTP_ACCEPTED:Response::HTTP_NO_CONTENT);
    }
}
