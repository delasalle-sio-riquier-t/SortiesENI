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
    private $selectEtat = "Publié";

    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repositoryEtat = $this->getDoctrine()->getRepository(Etat::class);
        $etats = $repositoryEtat->findAll();;
        $formBuilder = $this->createFormBuilder()
            ->add('Etat', ChoiceType::class, ['choices' => [
                'Publié' => 1,
                'Non Publié' => 2,
            ]]);
        $choix = $formBuilder->get('Etat')->getData();
        if ($choix!=null){
            $this->SetSelectEtat($choix);
        }
        $repositorySortie = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $repositorySortie->findSortiesByEtat($this->GetSelectEtat());
        $form = $formBuilder->getForm();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'sorties' => $sorties,
            'etats' => $etats,
            'form' =>$form->createView(),
            'selectEtat' => $this->GetSelectEtat(),
        ]);
    }

    public function GetSelectEtat(): string
    {
        return $this->selectEtat;
    }

    public function SetSelectEtat($newValue)
    {
        $this->selectEtat=$newValue;
    }
}
