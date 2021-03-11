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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
                $sortie->setEtatSortie(Etat::NO_PUBLISHED);

                $repo = $this->getDoctrine()->getRepository(Etat::class);
                $etat = $repo->find(Etat::NO_PUBLISHED);

                $sortie->setEtat($etat);

                //enregistrer ta sortie
                $em->persist($sortie);
                $em->flush();

                return $this->redirectToRoute('sortie_fiche', ['id'=>$sortie->getId()]);
            }

            if ($form->getClickedButton() === $form->get('publier')){
                // ...

                //Changer l'état et enregistrer
                $sortie->setEtatSortie(Etat::PUBLISHED);

                $repo = $this->getDoctrine()->getRepository(Etat::class);
                $etat = $repo->find(Etat::PUBLISHED);

                $sortie->setEtat($etat);

                $em->persist($sortie);
                $em->flush();

            }

            if ($form->getClickedButton() === $form->get('annuler')){
                // ...

                $this->redirectToRoute('index');

            }

        }

        return $this->render('sortie/actionsortie.html.twig', [
            'sortieForm' => $form->createView(),
            'typeAction' => (string) 'Création',
            'sortie'        => $sortie,
        ]);
    }

    /**
     * @Route("/sortie/{id}", name="sortie_fiche", requirements={"id":"\d+"})
     */
    public function update($id, Request $request, EntityManagerInterface $em): Response
    {

        //Creation d'une nouvelle sortie
        //repo Etat
        $repoEtat = $this->getDoctrine()->getRepository(Etat::class);

        //Repo Sortie
        $repoSortie = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $repoSortie->find($id);

        //Si pas trouver
        if(!$sortie)
            throw new NotFoundHttpException('Sortie not found');
        //page erreur personnalisé
//            return $this->render('sortie/erreurPage.html.twig', [
//                'codeErreur' => 'Code erreur xxx',
//                'msgErreur' => 'Exemple : Sortie non existante ou sortie ne vous appartenant pas',
//            ]);;

        //Si trouver mais pas organisateur on met en écriture sinon qu'en lecture avec des bouton d'aficchage etc
        if($sortie->getOrganisateur()->getId() == $this->getUser()->getId()) {

            //pour toute modif verification de la date de debut -24h ? afin depas modifier pendant la sortie et juste avant
            //enreg  : bouton grissé ou js pop up avec annonce -> vous ne pouvez pas modifier 24h avant etc
            //Publier : Bouton grisé ou js pop up avec annonce -> la sortie est déjà publié !

            //creation du formulaire
            $form = $this->createForm(SortieFormType::class, $sortie);
            $form->handleRequest($request);

            //condition si formulare validé etc alors on fait le traitement ce dessus pour l'add a la bdd !
            if ($form->isSubmitted() && $form->isValid()) {

                if ($form->getClickedButton() === $form->get('enregistrer')) {
                    // ...

                    //enregistrer ta sortie
                    $em->persist($sortie);
                    $em->flush();

                    //add flash msg "enreg terminer avec succes ou erreur etc"

                }

                if ($form->getClickedButton() === $form->get('publier')) {
                    // ...

                    //Changer l'état et enregistrer
                    $sortie->setEtatSortie(Etat::PUBLISHED);

                    $etat = $repoEtat->find(Etat::PUBLISHED);

                    $sortie->setEtat($etat);

                    $em->persist($sortie);
                    $em->flush();

                    //add flash msg "enreg terminer avec succes ou erreur etc"
                }

                if ($form->getClickedButton() === $form->get('annuler')) {
                    // ...
                    return $this->redirectToRoute('sortie_ficheAnnulation',['id' => $id]);

                }


                if ($form->getClickedButton() === $form->get('supprimer')) {
                    // ...
                    $em->remove($sortie);
                    $em->flush();
                    return $this->redirectToRoute('index');

                }
            }
            return $this->render('Sortie/actionsortie.html.twig', [
                'sortieForm'    => $form->createView(),
                'typeAction'    => (string) 'Modification',
                'sortie'        => $sortie,
            ]);
        }
        else
        {
            return $this->render('Sortie/ficheSortie.html.twig', [
                'sortie' => $sortie,
            ]);
        }
    }

    //delete ?
    /**
     * @Route("/annulationSortie/{id}", name="sortie_ficheAnnulation", requirements={"id":"\d+"})
     */
    public function annuler($id, Request $request, EntityManagerInterface $em): Response
    {


        $repoEtat   = $this->getDoctrine()->getRepository(Etat::class);
        $repoSortie = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie     = $repoSortie->find($id);


        //Suppression de la sortie si pas publier sinon -> annuler
        if($sortie && $this->getUser()->getId() == $sortie->getOrganisateur()->getId()) {

            if ( $sortie->getEtat()->getId() == Etat::NO_PUBLISHED) {
                $em->remove($sortie);
                $em->flush();
            }
            elseif($sortie->getEtat()->getId() == Etat::PUBLISHED) {
                //Changer l'état et enregistrer
                $sortie->setEtatSortie(Etat::CANCELED);

                $etat = $repoEtat->find(Etat::CANCELED);

                $sortie->setEtat($etat);

                $em->persist($sortie);
                $em->flush();

                //set etat en annule + envoi mail etc
                //

                return $this->redirectToRoute('sortie_fiche',['id' => $id]);
            }

            //add flash dans une modal box js avant le bouton qui fera appel au formulaire ?

            //add flash pour signaler le bon fonctionnement de l'action

            return $this->redirectToRoute('index');

        }
        elseif (!$sortie) {
            throw new NotFoundHttpException('Sortie not found');
        }else{
            throw new AccessDeniedHttpException('');
        }

    }
}
