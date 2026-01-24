<?php

namespace App\Controller;

use App\Class\Mail;
use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ForgotPasswordController extends AbstractController
{
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

                //  Send email with reset liknk to user containing the token 
                $mail = new Mail();
                $vars = [
                    'link' => 'link'
                ];

                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), "Réinitialisation de mot de passe", "forgotpassword.html", $vars);

            }

        }

        // 2- generate token and save it to the database with user info and expiration date


        // 3- send email with link to reset password (link contains the token) if email exists

        // 8- if email doesn't exist, display error messages as needed


        // Note: Ensure to implement security measures to protect against token misuse and expiration.
        // This is a simplified outline and should be expanded with proper validation, error handling, and security practices.
        // For production, consider using Symfony's built-in password reset features or bundles.
        // For now, just render a placeholder template.

        /**
         * 
         * TODO: Implement password reset functionality
         * 
         */
        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
        
    }

    /**
     * 4- User clicks on the link in the email
     * 5- Link directs to a route with the token as a parameter
     * 6- Validate the token (check if it exists, is associated with a user, and is not expired)
     * 7- If valid, display form to enter new password
     * 8- If invalid, display error message
     * 9- On form submission, update the user's password and invalidate the token
     */
    #[Route('/mot-de-passe/reset/{token}', name: 'app_password_reset')]
    public function update($token, UserRepository $userRepository): Response
    {
        // 4- form to enter new password (accessed via link with token)


        // 5- validate token and update password in the database


        // 6- notify user of successful password reset


        // 7- redirect to login page
        // $form = $this->createForm(ForgotPasswordType::class);
        
        // Validate the token
        // $user = $userRepository->findOneByResetToken($token);
        
        // if (!$user) {
        //     return $this->redirectToRoute('app_login');
        // }
        // Placeholder for password reset functionality
        return $this->render('password/reset.html.twig');
    }
}