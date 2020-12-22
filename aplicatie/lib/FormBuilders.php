<?php

namespace lib;


/**
 * in aceasta clasa definim  campurile de adaugare/modficare  din fiecare formular existent in aplicatie (gestionare produse, formular contact si administare useri)
 * campurile au denumirile coloanelor aferente din baza de date pentru a le putea mapa corespunzator
 */
class FormBuilders {

    public $produseBuilder = [
        'id_subcateg' => [
            'name' => 'id_subcateg', //valoarea atributului name
            'type' => 'choice', // tipul campului
            'init_data' => [], // valoarea atributului value
            'required' => 1, // daca campul este obligatoriu
            'multiple' => false,
            'expanded' => false,
            'label' => 'Categorie produs',
            'regex' => 'reg_b',
            'data' => [],
        ],
        'product_title' => [
            'name' => 'product_title',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Denumire produs',
            'regex' => 'reg_b',
        ],
        'product_description' => [
            'name' => 'product_description',
            'type' => 'textarea',
            'init_data' => '',
            'required' => 1,
            'label' => 'Descriere produs',
            'regex' => 'reg_desc',
        ],
        'category_p' => [
            'name' => 'category_p',
            'type' => 'choice',
            'init_data' => ['m' => 'm',
                'f' => 'f',
            ],
            'required' => 1,
            'multiple' => true,
            'expanded' => true,
            'label' => 'Categorie client',
            'regex' => 'reg_mf',
            'data' => [],
        ],
        'product_price' => [
            'name' => 'product_price',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Pret',
            'regex' => 'reg_pret',
        ],
        'product_discount' => [
            'name' => 'product_discount',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Discount',
            'regex' => 'reg_discount',
        ],
        'product_image' => [
            'name' => 'product_image',
            'type' => 'file',
            'init_data' => '',
            'required' => 1,
            'label' => 'Imagine',
            'regex' => '',
        ],
    ];
    public $userBuilder = [
        'prenume' => [
            'name' => 'prenume',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Prenume',
            'regex' => 'reg_nume',
        ],
        'nume' => [
            'name' => 'nume',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Nume',
            'regex' => 'reg_nume',
        ],
        'parola' => [
            'name' => 'parola',
            'type' => 'password',
            'init_data' => '',
            'required' => 1,
            'label' => 'Parola',
            'regex' => 'reg_parola',
        ],
        'email' => [
            'name' => 'email',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Email',
            'regex' => 'reg_email',
        ],
        'oras' => [
            'name' => 'oras',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Oras',
            'regex' => 'reg_oras',
        ],
        'tara' => [
            'name' => 'tara',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Tara',
            'regex' => 'reg_oras',
        ],
        'adresa_livrare' => [
            'name' => 'adresa_livrare',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Adresa livrare',
            'regex' => 'reg_desc',
        ],
        'telefon' => [
            'name' => 'telefon',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Telefon',
            'regex' => 'reg_tel',
        ],
        'drepturi' => [
            'name' => 'drepturi',
            'type' => 'choice', // camp de tip alegere
            'init_data' => [
                'normal' => 'Normal',
                'operator' => 'Operator',
                'admin' => 'Administrator',
            ],
            'multiple' => true,
            'expanded' => true,
            'required' => 1,
            'label' => 'Alege drepturi',
            'data' => [], // aici vom pune vaorile primite prin $_POST
            'regex' => 'reg_drepturi',
        ],
    ];
    public $contactBuilder = [
        'nume' => [
            'name' => 'nume',
            'type' => 'text',
            'init_data' => '',
            'label' => 'Nume',
            'required' => 1,
            'regex' => 'reg_nume',
        ],
        'email' => [
            'name' => 'email',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Email',
            'regex' => 'reg_email',
        ],
        'telefon' => [
            'name' => 'telefon',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Telefon',
            'regex' => 'reg_tel',
        ],
        'message' => [
            'name' => 'message',
            'type' => 'textarea',
            'init_data' => '',
            'required' => 1,
            'label' => 'Mesaj',
            'regex' => 'reg_desc',
        ],
        'subiect' => [
            'name' => 'subiect',
            'type' => 'text',
            'init_data' => '',
            'required' => 1,
            'label' => 'Subiect',
            'regex' => 'reg_desc',
        ],
    ];

}
