<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class OrderController
{
    private $ctx;

    public function __construct($ctx)
    {
        $this->ctx = $ctx;
    }

    public function getAllStates(Request $request, Response $response, array $args)
    {
        $stmt = $this->ctx->db->prepare('SELECT * FROM užsakymo_būsenos;');
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }

    public function getState(Request $request, Response $response, array $args) {
        //TODO: 404
        $stmt = $this->ctx->db->prepare('SELECT * FROM užsakymo_būsenos WHERE id = :id;');
        $stmt->bindParam(':id', $args['id']);
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }
}