<?php

namespace App\Controller;

use App\Class\Mail;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ForgotPasswordController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/mot-de-passe-oublie', name: 'app_forgot_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        // 1- form to enter email
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            // Check if user with this email exists
            $user = $userRepository->findOneByEmail($email);

            // flash massage to notice that the email was submitted succefuly
            $this->addFlash('success', 'Si un compte avec cet email existe, un lien de réinitialisation a été envoyé.');

            // Here, we just check if user exist to avoid email enumeration attacks before proceeding with password reset process.
            if ($user) {
                
                // 2- generate token and save it to the database with user info and expiration date
                $token = bin2hex(random_bytes(32));
                $user->setToken($token);
                
                $date = new DateTime();
                $date->modify('+2 minutes');
                $user->setTokenExpireAt($date);
                $this->em->flush();

                // 3- send email with link (URL) to reset password (link contains the token) if email exists
                $mail = new Mail();
                $vars = [
                    'link' => $this->generateUrl('app_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
                ];

                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), "Réinitialisation de mot de passe", "forgotpassword.html", $vars);

            }

        }

        // Note: Ensure to implement security measures to protect against token misuse and expiration.
        // This is a simplified outline and should be expanded with proper validation, error handling, and security practices.
        // For production, consider using Symfony's built-in password reset features or bundles.
        // For now, just render a placeholder template.

        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
        
    }

    /**
     * - User clicks on the link in the email
     * - Link directs to a route with the token as a parameter
     * - Validate the token (check if it exists, is associated with a user, and is not expired)
     * - If valid, display form to enter new password
     * - If invalid, display error message
     * - On form submission, update the user's password and invalidate the token
     */
    #[Route('/mot-de-passe/reset/{token}', name: 'app_password_reset')]
    public function update(Request $request, UserRepository $userRepository, string $token): Response
    {
        // Check if token is present
        if (!$token) {
            return $this->redirectToRoute('app_forgot_password');
        }

        // verification of the token by finding the user associated with it in database
        $user = $userRepository->findOneByToken($token);
        
        // check if token is expired by comparing current date (now) with token expiration date
        $now = new DateTime();
        if (!$user || $now > $user->getTokenExpireAt()) {
            $this->addFlash('danger', 'Le lien de réinitialisation est invalide ou a expiré.');
            return $this->redirectToRoute('app_forgot_password');
        }
        
        // form to enter new password (accessed via link with token)
        $form = $this->createForm(ResetPasswordType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès. Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password/reset.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}