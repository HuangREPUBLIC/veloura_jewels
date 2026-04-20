<?php
declare(strict_types=1);

namespace App\Controller;

class FaqController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index']);
    }

    public function index()
    {
        $faqs = [
            [
                'question' => 'What products does Veloura Jewels sell?',
                'answer' => 'Veloura Jewels offers handcrafted jewellery and home décor pieces.'
            ],
            [
                'question' => 'How can I contact Veloura Jewels?',
                'answer' => 'You can contact us via the Contact page.'
            ],
            [
                'question' => 'Are your products handmade?',
                'answer' => 'Yes, all our products are handcrafted.'
            ]
        ];

        $this->set(compact('faqs'));
    }
}
