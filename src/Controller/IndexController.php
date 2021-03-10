<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repositorySortie = $this->getDoctrine()->getRepository(Sortie::class);
        $sorties = $repositorySortie->findSortiesByEtat(1);
        $repositoryEtat = $this->getDoctrine()->getRepository(Etat::class);
        $etats=$repositoryEtat->findAll();
        $form = $this->createFormBuilder()
            ->add('Filtre', ChoiceType::class, [
                'choices' => [
                    function() use ($etats) {
                        return $etats;
                    }
                ]])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'sorties' => $sorties,
            'filtreForm' => $form->createView(),
        ]);
    }
}
