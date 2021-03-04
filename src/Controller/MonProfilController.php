<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantProfilFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonProfilController extends AbstractController
{
    /**
     * @Route("/mon/profil", name="mon_profil")
     */
    public function MonProfil(Request $request, SluggerInterface $slugger, EntityManager $em): Response
    {

        $participant = new Participant();
        $form = $this->createForm(ParticipantProfilFormType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PhotoFile $photoFile */
            $photoFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoFile->move(
                        $this->getParameter('Photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'photoFilename' property to store the png /jpeg file name
                // instead of its contents
                $participant->setBrochureFilename($newFilename);
            }

            // ... persist the $product variable or any other work
            
            //add sql request update. Maybe check for the new participant to be an existant object and not a new one

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('mon_profil/Profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
