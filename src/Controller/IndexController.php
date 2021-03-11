<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    private $selectEtat = 1;

    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repositoryEtat = $this->getDoctrine()->getRepository(Etat::class);
        $etats = $repositoryEtat->findAll();
        $formBuilder = $this->createFormBuilder()
            ->add('Etat', ChoiceType::class, ['choices' => [
                'Publié' => Etat::PUBLISHED,
                'Non Publié' => Etat::NO_PUBLISHED,
                'Annulé'=>Etat::CANCELED,
                'Cloturé'=>Etat::CLOSED,
                'Archivé'=>Etat::ARCHIVED,
            ]]);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->SetSelectEtat($form->get('Etat')->getData());
        }
        $repositorySortie = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $repositorySortie->findSortiesByEtat($this->GetSelectEtat());
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'sorties' => $sorties,
            'etats' => $etats,
            'form' =>$form->createView(),
        ]);
    }

    public function GetSelectEtat(): int
    {
        return $this->selectEtat;
    }

    public function SetSelectEtat($newValue)
    {
        $this->selectEtat=$newValue;
    }
}
