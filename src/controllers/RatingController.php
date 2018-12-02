<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class RatingController
{
    private $ctx;

    public function __construct($ctx)
    {
        $this->ctx = $ctx;
    }
}