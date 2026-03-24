<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;

/**
 * Turnstile Component
 */
class TurnstileComponent extends Component
{
    /**
     * Validate Turnstile token returned from the client page (validate CAPTCHA)
     *
     * @param string $token Turnstile token
     * @param string|null $remoteIp IP address of the visitor
     * @return mixed|array Validation results from CloudFlare
     */
    public function validateTurnstile(string $token, ?string $remoteIp = null)
    {
        $data = [
            'secret' => Configure::read('Captcha.turnstile.secretKey'),
            'response' => $token,
        ];

        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        $response = file_get_contents(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            false,
            stream_context_create([
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ],
            ]),
        );

        if ($response === false) {
            return ['success' => false, 'error-codes' => ['internal-error']];
        }

        return json_decode($response, true);
    }
}
