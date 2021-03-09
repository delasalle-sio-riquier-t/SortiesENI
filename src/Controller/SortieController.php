<?php

namespace App\Controller;

use App\Entity\Etat;
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

            if ($form->getClickedButton() === $form->get('enregistrer')){
                // ...
                //Pas utile
                $sortie->setEtatSortie(0);

                $repo = $this->getDoctrine()->getRepository(Etat::class);
                $etat = $repo->find('2');

                $sortie->setEtat($etat);

                //enregistrer ta sortie
                $em->persist($sortie);
                $em->flush();

                return $this->redirectToRoute('sortie_fiche', ['id'=>$sortie->getId()]);
            }

            if ($form->getClickedButton() === $form->get('publier')){
                // ...

                //Changer l'état et enregistrer
                $em->persist($sortie);
                $em->flush();

            }

            if ($form->getClickedButton() === $form->get('annuler')){
                // ...

                //Annuler ? supprimer ?
                $em->persist($sortie);
                $em->flush();

            }

        }


        return $this->render('sortie/actionsortie.html.twig', [
            'sortieForm' => $form->createView(),
            'typeAction' => (string) 'Création',
        ]);
    }

    /**
     * @Route("/sortie/{id}", name="sortie_fiche", requirements={"id":"\d+"})
     */
    public function update($id, Request $request, EntityManagerInterface $em): Response
    {

        //Creation d'une nouvelle sortie
        $repo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $repo->find($id);


        //creation du formulaire
        $form = $this->createForm( SortieFormType::class, $sortie);
        $form->handleRequest($request);

        //condition si formulare validé etc alors on fait le traitement ce dessus pour l'add a la bdd !
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->getClickedButton() === $form->get('enregistrer')){
                // ...
                //Pas utile
                $sortie->setEtatSortie(0);

                $repo = $this->getDoctrine()->getRepository(Etat::class);
                $etat = $repo->find('2');

                $sortie->setEtat($etat);

                //enregistrer ta sortie
                $em->persist($sortie);
                $em->flush();

                //add flash msg "enreg terminer avec succes ou erreur etc"
                return $this->redirectToRoute('sortie_fiche', ['id'=>$sortie->getId()]);
            }

            if ($form->getClickedButton() === $form->get('publier')){
                // ...

                //Changer l'état et enregistrer
                $em->persist($sortie);
                $em->flush();

            }

            if ($form->getClickedButton() === $form->get('annuler')){
                // ...

                //Annuler ? supprimer ?
                $em->persist($sortie);
                $em->flush();

            }

        }

        return $this->render('Sortie/actionsortie.html.twig', [
            'sortieForm' => $form->createView(),
            'typeAction' => (string) 'Modification',
            'idSortie'   => $sortie->getId(),
        ]);
    }

    //delete ?
}
