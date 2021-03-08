<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        //Creation d'une nouvelle sortie
        $sortie = new Sortie();

        //set l'organisateur par l'user courant
        $sortie->setOrganisateur($this->getUser());

        //creation du formulaire
        $form = $this->createForm( SortieFormType::class,$sortie);
        $form->handleRequest($request);

        //condition si formulare validé etc alors on fait le traitement ce dessus pour l'add a la bdd !
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($sortie);
            $em->flush();

        }


        return $this->render('sortie/actionsortie.html.twig', [
            'sortieForm' => $form->createView(),
            'typeAction' => (string) 'Création',
        ]);
    }

    /**
     * @Route("/sortie/update", name="sortie_update")
     */
    public function update(): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm();


        return $this->render('Sortie/Sortie.html.twig', [
            'sortieForm' => $form->createView(),
            'typeAction' => (string) 'Modification',
            'idSortie'   => $sortie->getId(),
        ]);
    }

    //delete ?
}
