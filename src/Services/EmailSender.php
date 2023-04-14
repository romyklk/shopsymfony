<?php

namespace App\Services;

use Mailjet\Client;
use App\Entity\User;
use App\Entity\Order;
use Mailjet\Resources;
use App\Entity\Contact;
use App\Entity\EmailModel;

class EmailSender
{
    // Méthode pour envoyer un email avec Mailjet Après avoir éffectué une commande
    /*   public function sendEmailWithMailjet(User $user, EmailModel $email, Order $order)
    {

        $mj = new Client($_ENV["MAILJET_APIKEY_PUBLIC"], $_ENV["MAILJET_APIKEY_PRIVATE"], true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "romyklk2210+mailjet@gmail.com",
                        'Name' => "Sym Shop"
                    ],
                    'To' => [
                        [
                            'Email' =>  $user->getEmail(),
                            'Name' => $user->getFullName(),
                        ]
                    ],
                    'TemplateID' => 4732745,
                    'TemplateLanguage' => true,
                    'Subject' => "Confirmation de votre commande sur www.symshop.fr",
                    'Variables' => [
                        "username" => $user->getFullName(),
                        "ordernumber" => $order->getReference(),
                        "orderdate" => $order->getCreatedAt(),
                        "productlist" => $order->getOrderDetails(),
                        "orderamount" => $order->getSubtotalTTC(),
                        "paymentmethode" => 'Carte bancaire',
                        "shippingadress" => $order->getDeliveryAddress()
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && ($response->getData());
    } */


  /*   // Méthode pour envoyer un email avec Mailjet depuis le formulaire de contact
    public function sendEmailWithMailjetFromContactForm(Contact $contact, EmailModel $email)
    {

        $mj = new Client($_ENV["MAILJET_APIKEY_PUBLIC"], $_ENV["MAILJET_APIKEY_PRIVATE"], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "romyklk2210+mailjet@gmail.com",
                        'Name' => "Sym Shop"
                    ],
                    'To' => [
                        [
                            'Email' => $contact->getEmail(),
                            'Name' => $contact->getName(),
                        ]
                    ],
                    'TemplateID' => 4733216,
                    'TemplateLanguage' => true,
                    // 'Subject' => "Réponse à votre demande via la page de contact de www.symshop.fr",
                    'Variables' => [
                        "subject" => $contact->getSubject(),
                        "username" => $contact->getName(),
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && ($response->getData());
    }

 */
    // Méthode pour envoyer un email avec Mailjet Après avoir éffectué une commande
    public function sendEmailWithMailjet(User $user, EmailModel $email, Order $order)
    {

        // // Stocker le résultat de la méthode dans une variable
        $deliveryAddress = $order->getDeliveryAddress();

        // Effectuer le remplacement de la chaîne de caractères
        $deliveryAddress = str_replace('[goline]', '<br> ', $deliveryAddress);

        $amount = $order->getSubtotalTTC() / 100;

        $amount = number_format($amount, 2, ',', ' ');

        // Formater la date au format français
        $orderDate = $order->getCreatedAt();

        $orderDate = $orderDate->format('d/m/Y');

        $mj = new Client($_ENV["MAILJET_APIKEY_PUBLIC"], $_ENV["MAILJET_APIKEY_PRIVATE"], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "shop@symshop.romy-dev.me",
                        'Name' => "Sym Shop"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFullName(),
                        ]
                    ],
                    'TemplateID' => 4733268,
                    'TemplateLanguage' => true,
                    'Subject' => "Confirmation de votre commande sur www.symshop.fr",
                    'Variables' => [
                        "username" => $user->getFullName(),
                        "orderNumber" => $order->getReference(),
                        "orderdate" => $orderDate,
                        "orderAmount" =>  $amount . ' €',
                        "shippingadress" => $deliveryAddress
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && ($response->getData());
    }


    // Méthode pour envoyer un email de notification à l'administrateur

    public function sendEmailToAdmin(User $user, EmailModel $email, Order $order)
    {
        $amount = $order->getSubtotalTTC() / 100;

        $amount = number_format($amount, 2, ',', ' ');

        $mj = new Client($_ENV["MAILJET_APIKEY_PUBLIC"], $_ENV["MAILJET_APIKEY_PRIVATE"], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "shop@symshop.romy-dev.me",
                        'Name' => "Sym Shop"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFullName(),
                        ]
                    ],
                    'TemplateID' => 4733443,
                    'TemplateLanguage' => true,
                    'Subject' => "Notification de vente sur www.symshop.fr",
                    'Variables' => [
                        "orderAmount"  => $amount . ' €',
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && ($response->getData());
    }
}
