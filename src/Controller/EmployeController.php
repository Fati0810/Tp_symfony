<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeFormType;
use App\Controller\EmployeController;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }

    # afficher la liste des employes avec /
    /**
     * @Route("/", name="employe_list")
     */
    public function employeList(EmployeRepository $employeRepository)
    {
        $employes = $employeRepository->findAll();

        return $this->render("employe_list.html.twig", ['employes' => $employes]);
    }

    # afficher un employe avec son id

    /**
     * @Route("/employe/{id}", name="employe_show")
     */
    public function employeShow(EmployeRepository $employeRepository, $id)
    {
        $employe = $employeRepository->find($id);

        return $this->render("employe_show.html.twig", ['employe' => $employe]);
    }

    # créer/ajouter

     /**
     * @Route("/create", name="employe_create")
     */
    public function employeCreate(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $employe = new Employe();

        $employeForm = $this->createForm(EmployeFormType::class, $employe);

        $employeForm->handleRequest($request);

        if ($employeForm->isSubmitted() && $employeForm->isValid()) {
            $entityManagerInterface->persist($employe);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('employe_list');
        }

        return $this->render("employe_form.html.twig", ['employeForm' => $employeForm->createView()]);
    }

    # modifier
    /**
     * @Route("/{id}/update", name="employe_update")
     */
    public function employeUpdate(
        $id,
        EmployeRepository $employeRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {

        $employe = $employeRepository->find($id);

        $employeForm = $this->createForm(EmployeFormType::class, $employe);

        $employeForm->handleRequest($request);

        if ($employeForm->isSubmitted() && $employeForm->isValid()) {
            $entityManagerInterface->persist($employe);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('employe_list');
        }

        return $this->render("employe_form.html.twig", ['employeForm' => $employeForm->createView()]);
    }

    
    # supprimer
    /**
     * @Route("/delete/employe/{id}", name="employe_delete")
     */
    public function deleteEmploye(
        $id,
        EntityManagerInterface $entityManagerInterface,
        EmployeRepository $employeRepository
    ) {
        $employe = $employeRepository->find($id);

        $entityManagerInterface->remove($employe);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('employe_list');
    }
}