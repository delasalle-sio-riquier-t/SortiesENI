<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(EntityManagerInterface $em): Response
    {
        //Creation d'une nouvelle sortie
        $sortie = new Sortie();

        //recup de l'user courant
        $test = $this->getUser();

        //set l'organisateur par l'user courant
        $sortie->setOrganisateur($test);

        //creation du formulaire
        $form = $this->createForm( SortieFormType::class,$sortie);

        //condition si formulare validé etc alors on fait le traitement ce dessus pour l'add a la bdd !

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
