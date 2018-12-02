<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

use Mailgun\Mailgun;

class ReservationController
{
    private $ctx;

    public function __construct($ctx)
    {
        $this->ctx = $ctx;
    }

    public function getAllStates(Request $request, Response $response, array $args)
    {
        $stmt = $this->ctx->db->prepare('SELECT * FROM rezervacijos_būsenos;');
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }

    public function getState(Request $request, Response $response, array $args) {
        //TODO: 404
        $stmt = $this->ctx->db->prepare('SELECT * FROM rezervacijos_būsenos WHERE id = :id;');
        $stmt->bindParam(':id', $args['id']);
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }

    public function get(Request $request, Response $response, array $args) {
        $stmt = $this->ctx->db->prepare('SELECT id, paštas, data, žmonių_skaičius, pradžia, pabaiga, būsena, fk_staliukas FROM rezervacijos WHERE id = :id;');
        $stmt->bindParam(':id', $args['id']);
        $stmt->execute();

        return $response->withJson($stmt->fetch());
    }

    public function create(Request $request, Response $response, array $args) {
        //TODO: validate
        $randomPass = bin2hex(random_bytes(8));
        $passwordHash = password_hash($randomPass, PASSWORD_BCRYPT);

        $params = $request->getParsedBody();
        $state = 2;
        $admin = 1;

        $stmt = $this->ctx->db->prepare(
            'INSERT INTO rezervacijos (
                paštas,
                slaptažodis,
                data,
                žmonių_skaičius,
                pradžia,
                pabaiga,
                būsena,
                fk_staliukas,
                fk_darbuotojas
            ) VALUES (
                :mail,
                :pass,
                NOW(),
                :ppl,
                :start,
                :end,
                :state,
                :table,
                :admin
            );');

        $stmt->bindParam(':mail', $params['email']);
        $stmt->bindParam(':pass', $passwordHash);
        $stmt->bindParam(':ppl', $params['people']);
        $stmt->bindParam(':start', $params['from']);
        $stmt->bindParam(':end', $params['to']);
        $stmt->bindParam(':state', $state); //TODO: approve by admin?
        $stmt->bindParam(':table', $params['table']);
        $stmt->bindParam(':admin', $admin); //TODO: approve by admin?
        
        $stmt->execute();

        $id = $this->ctx->db->lastInsertId();

        $mg = Mailgun::create('key-d3055fc96350349d7adc5bd173e27e0c');

        $mg->messages()->send('sandbox491d9c6765054fca8a1c365e6f9c88c0.mailgun.org', [
            'from'    => 'noreply@jalapenopizza.lt',
            'to'      => $params['email'],
            'subject' => 'Jūsų rezervacija sukurta!',
            'text'    => 'Sveiki, jūsų rezervacijos kodas: '.$randomPass
          ]);

        return $response->withJson(array("id" => (int)$id), 201);
    }

    public function delete(Request $request, Response $response, array $args) {
        //TODO: validate
        $stmt = $this->ctx->db->prepare('UPDATE rezervacijos SET būsena = :state WHERE id = :id');

        $state = 4;

        $stmt->bindParam(':id', $args['id']);
        $stmt->bindParam(':state', $state);
        $stmt->execute();

        return $response->withStatus(204);
    }
}