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
                'question' => 'What types of products does Veloura Jewels offer?',
                'answer' => 'Veloura Jewels offers handcrafted jewellery and home décor pieces designed to add elegance and individuality to your everyday life.'
            ],
            [
                'question' => 'Are all Veloura Jewels products handmade?',
                'answer' => 'Yes, our products are carefully handcrafted to create unique pieces with a personal and artistic touch.'
            ],
            [
                'question' => 'How can I get in touch with Veloura Jewels?',
                'answer' => 'You can contact us through the Contact page by submitting your name, email address, and message.'
            ],
            [
                'question' => 'Where can I browse your collections?',
                'answer' => 'You can explore our products through the Jewelry and Home Decor pages available on the website.'
            ],
            [
                'question' => 'Can I enquire about a product before purchasing?',
                'answer' => 'Yes, you are welcome to contact us if you would like more information about a product before making a purchase.'
            ]
        ];

        $this->set(compact('faqs'));
    }
}
