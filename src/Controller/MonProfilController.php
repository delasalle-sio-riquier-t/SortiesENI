<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


class MonProfilController extends AbstractController
{
    /**
     * @Route("/monProfil", name="mon_profil")
     */
    public function MonProfil(Request $request, EntityManagerInterface $em): Response
    {
        //recupération des informations concernant l'utilisateur
        $participant = $this->getUser();

        $form = $this->createForm(ParticipantProfilFormType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    //Ajouter une exception ici !
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $participant->setPhoto($newFilename);
            }

            // ... persist the $product variable or any other work

            //MEP
            $participant->SetPassword($form->get('password'));
            //update ici !
            $em->flush();

            //add sql request update. Maybe check for the new participant to be an existant object and not a new one


            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('mon_profil/Profil.html.twig', [
            'participantForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/monProfilUpdate", name="mon_profilUpdate")
     */
    public function MonProfilUpdate(Request $request, EntityManagerInterface $em): Response
    {

        $participant = new Participant();
        $form = $this->createForm(ParticipantProfilFormType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('brochure')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photoFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $participant->setPhoto($newFilename);
            }

            // ... persist the $product variable or any other work

            //add sql request update. Maybe check for the new participant to be an existant object and not a new one

            return $this->redirectToRoute('mon_profil');
        }

        return $this->render('mon_profil/Profil.html.twig', [
            'participantForm' => $form->createView(),
        ]);
    }
}
