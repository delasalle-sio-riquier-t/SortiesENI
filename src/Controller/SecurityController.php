<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ResetPassType;
use App\Repository\ParcticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

//    /**
//     * @Route('/oublie-pass', name = "app_forgotten_password")
//     */
//    public function forgottenPass (Request $request,ParcticipantRepository $parcticipantRepo,\Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator){
//
//        $form = $this->createForm(ResetPassType::class);
//
//        // On traite le formulaire
//        $form->handleRequest($request);
//
//        // Si le formulaire est valide
//        if ($form->isSubmitted() && $form->isValid()) {
//            // On récupère les données
//            $donnees = $form->getData();
//
//            // On cherche un utilisateur ayant cet e-mail
//            $user = new Participant();
//            $user = $parcticipantRepo->findOneBy(['mail'],$donnees['email']);
//
//            // Si l'utilisateur n'existe pas
//            if ($user === null) {
//                // On envoie une alerte disant que l'adresse e-mail est inconnue
//                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
//
//                // On retourne sur la page de connexion
//                return $this->redirectToRoute('app_login');
//            }
//
//            // On génère un token
//            $token = $tokenGenerator->generateToken();
//
//            // On essaie d'écrire le token en base de données
//            try{
//                $user->setResetToken($token);
//                $entityManager = $this->getDoctrine()->getManager();
//                $entityManager->persist($user);
//                $entityManager->flush();
//            } catch (\Exception $e) {
//                $this->addFlash('warning', $e->getMessage());
//                return $this->redirectToRoute('app_login');
//            }
//
//            // On génère l'URL de réinitialisation de mot de passe
//            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
//
//            // On génère l'e-mail
//            $message = (new \Swift_Message('Mot de passe oublié'))
//                ->setFrom('votre@adresse.fr')
//                ->setTo($user->getMail())
//                ->setBody(
//                    "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site Nouvelle-Techno.fr. Veuillez cliquer sur le lien suivant : " . $url,
//                    'text/html'
//                )
//            ;
//
//            // On envoie l'e-mail
//            $mailer->send($message);
//
//            // On crée le message flash de confirmation
//            $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');
//
//            // On redirige vers la page de login
//            return $this->redirectToRoute('app_login');
//        }
//
//        // On envoie le formulaire à la vue
//        return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
//    }
}
