<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Response;

class ChatController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['message']);
    }

    public function message(): Response
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->disableAutoLayout();

        $data        = $this->request->getData();
        $history     = $data['history'] ?? [];
        $userMessage = trim($data['message'] ?? '');

        if (empty($userMessage)) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Empty message']));
        }

        // Build messages array from history + new message
        $messages = [];
        foreach ($history as $item) {
            if (!empty($item['role']) && !empty($item['content'])) {
                $messages[] = [
                    'role'    => $item['role'],
                    'content' => $item['content'],
                ];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $apiKey = Configure::read('Groq.apiKey');

        $payload = json_encode([
            'model' => 'llama-3.1-8b-instant',
            'messages' => array_merge(
                [[
                    'role'    => 'system',
                    'content' => "You are a friendly and knowledgeable jewelry advisor for Veloura Jewels, a handcrafted jewelry and home décor boutique based in Melbourne, Australia. " .
                        "Founded by Sarah Smith in Brooksdale, Veloura Jewels specialises in unique, handcrafted pieces that blend creativity and meaningful design. " .
                        "Opening hours are 10:00AM–6:00PM. Phone: 123 456 7890. Email: veloura.jewels@gmail.com. Address: 88 Elizabeth Road, Melbourne VIC 3000. " .
                        "Help customers with questions about our jewelry, custom pieces, orders, shipping, and care tips. " .
                        "Keep responses warm, concise, and helpful. If you don't know something specific, invite them to contact us directly.",
                ]],
                $messages
            ),
        ]);

        $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_TIMEOUT        => 30,
        ]);

        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'API error', 'code' => $httpCode]));
        }

        $decoded = json_decode($result, true);
        $reply   = $decoded['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['reply' => $reply]));
    }
}
