<?php

namespace App\Class;
class State
{
    /**
     * @var array<int, array<string, string>>
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