<?php


namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class ReportController {
    private $ctx;

    public function __construct($ctx)
    {
        $this->ctx = $ctx;
    }
}
