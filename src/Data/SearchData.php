<?php

namespace App\Data;


use phpDocumentor\Reflection\Types\Boolean;

class SearchData
{
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var array
     */
    public $campus = [];

    /**
     * @var bool
     */
    public $organisateur = true;

    /**
     * @var bool
     */
    public $isInscrit = true;

    /**
     * @var bool
     */
    public $sortieTerminees = true;



    public $date = 'now';




}