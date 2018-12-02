<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class TableController
{
    private $ctx;

    public function __construct($ctx)
    {
        $this->ctx = $ctx;
    }

    public function getAll(Request $request, Response $response, array $args) {
        //TODO: based on datetime
        $stmt = $this->ctx->db->prepare('SELECT *, true as laisvas FROM staliukai;');
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }
}