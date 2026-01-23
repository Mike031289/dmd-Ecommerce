<?php

namespace App\Class;
class State
{
    /**
     * @var array<int, array<string, string>>
     * State constants for order statuses
     * 2 => In preparation
     * 3 => Shipped
     * 4 => Cancelled
     * Each state has a label, email subject, and email template associated with it.
     */
    public const STATE = [
        2 => [
            'label' => 'En cours de préparation',
            'email_subject' => 'Commande en cours de préparation',
            'email_template' => 'order_state_2.html',
        ],
        3 => [
            'label' => 'Expédiée',
            'email_subject' => 'Commande expédiée',
            'email_template' => 'order_state_3.html',
        ],
        4 => [
            'label' => 'Annulée',
            'email_subject' => 'Commande annulée',
            'email_template' => 'order_state_4.html',
        ]
    ];
}