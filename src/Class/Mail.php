<?php 

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

/**
 * Minimal Mail utility class to satisfy usage in the controller.
 * Placed in the same namespace so new Mail() resolves to App\Controller\Mail.
 * Replace or move this implementation to a proper service and inject it when integrating with Symfony Mailer.
 */
Class Mail
{
    /**
     * Send a simple HTML email using PHP mail().
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlContent
     * @return bool
     */
    public function send(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        $to = trim($toEmail);
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        // Optionally include recipient name
        if ($toName !== '') {
            $to = sprintf('%s <%s>', $toName, $to);
        }
        
        // / Use your saved credentials, specify that you are using Send API v3.1 /
        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['MAIL_FROM_EMAIL'],
                        'Name' => $_ENV['MAIL_FROM_NAME']
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 7503238,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $htmlContent
                    ],
                ]
                ], 
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        return $response->success();
    }   
}