<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class EmployeeController {
    private $ctx;

    public function __construct($ctx)
    {
        $this->ctx = $ctx;
    }
    
    
    public function getAllStates(Request $request, Response $response, array $args)
    {
        $stmt = $this->ctx->db->prepare('SELECT * FROM darbuotojo_rolės;');
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }

    public function getState(Request $request, Response $response, array $args) {
        //TODO: 404
        $stmt = $this->ctx->db->prepare('SELECT * FROM darbuotojo_rolės WHERE id = :id;');
        $stmt->bindParam(':id', $args['id']);
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }
    //returnina viska be slaptazodzio
    public function getAll(Request $request, Response $response, array $args) {
        //TODO: based on datetime
        $stmt = $this->ctx->db->prepare('SELECT `vardas`, `pavardė`, `asmens_kodas`, `paštas`, `atlyginimas`, `adresas`, `gimimo_data`, `įdarbinimo_data`, `atleidimo_data`, `sąskaitos_nr`, `pareigos`, `id` FROM darbuotojai;');
        $stmt->execute();

        return $response->withJson($stmt->fetchAll());
    }
    public function get(Request $request, Response $response, array $args) {
        $stmt = $this->ctx->db->prepare('SELECT `id`, `vardas`, `pavardė`, `asmens_kodas`, `paštas`, `atlyginimas`, `adresas`, `gimimo_data`, `įdarbinimo_data`, `atleidimo_data`, `sąskaitos_nr`, `pareigos` FROM darbuotojai WHERE id = :id;');
        $stmt->bindParam(':id', $args['id']);
        $stmt->execute();

        return $response->withJson($stmt->fetch());
    }
    public function create(Request $request, Response $response, array $args) {
        //TODO: validate
        $randomPass = bin2hex(random_bytes(8));
        $passwordHash = password_hash($randomPass, PASSWORD_BCRYPT);

        $params = $request->getParsedBody();


        $stmt = $this->ctx->db->prepare(
            'INSERT INTO darbuotojai (
                `vardas`,
                `pavardė`,
                `asmens_kodas`, 
                `paštas`,
                `slaptažodis`, 
                `atlyginimas`, 
                `adresas`, 
                `gimimo_data`,
                `įdarbinimo_data`, 
                `atleidimo_data`, 
                `sąskaitos_nr`, 
                `pareigos`
                )
                VALUES (
                :firstName,
                :lastName,
                :IdentificationCode,
                :mail,
                :pass,
                :salary,
                :adress,
                :dob,
                NOW(),
                NULL,
                :creditCard,
                :pareigos
            );');

        $stmt->bindParam(':firstName', $params['firstName']);
        $stmt->bindParam(':lastName', $params['lastName']);
        $stmt->bindParam(':IdentificationCode', $params['IdentificationCode']);
        $stmt->bindParam(':mail', $params['mail']);
        $stmt->bindParam(':pass', $passwordHash);
        $stmt->bindParam(':salary', $params['salary']);
        $stmt->bindParam(':adress', $params['adress']);
        $stmt->bindParam(':dob', $params['dob']);
        $stmt->bindParam(':creditCard', $params['creditCard']);
        $stmt->bindParam(':pareigos', $params['pareigos']);
        $stmt->execute();

        $id = $this->ctx->db->lastInsertId();


        return $response->withJson(array("id" => (int)$id), 201);
    }
    public function delete(Request $request, Response $response, array $args) {
        //TODO: validate
        $stmt = $this->ctx->db->prepare('UPDATE darbuotojai SET atleidimo_data = NOW() WHERE id = :id');

        $stmt->bindParam(':id', $args['id']);
        $stmt->execute();
        
        

        return $response->withStatus(204);
    }
    public function update(Request $request, Response $response, array $args) {
        //TODO: validate
        $stmt = $this->ctx->db->prepare(
                'UPDATE darbuotojai SET 
                    vardas = :firstName,
                    pavardė = :lastName,
                asmens_kodas = :IdentificationCode, 
                paštas = :mail,
                atlyginimas = :salary, 
                adresas = :adress, 
                gimimo_data = :dob,
                sąskaitos_nr = :creditCard, 
                pareigos  = :pareigos
                WHERE
                id = :id');
        
        $stmt->bindParam(':id', $args['id']);
        $stmt->bindParam(':firstName', $params['firstName']);
        $stmt->bindParam(':lastName', $params['lastName']);
        $stmt->bindParam(':IdentificationCode', $params['IdentificationCode']);
        $stmt->bindParam(':mail', $params['mail']);
        $stmt->bindParam(':salary', $params['salary']);
        $stmt->bindParam(':adress', $params['adress']);
        $stmt->bindParam(':dob', $params['dob']);
        $stmt->bindParam(':creditCard', $params['creditCard']);
        $stmt->bindParam(':pareigos', $params['pareigos']);
        $stmt->execute();
        
        return $response->withStatus(204);
    }
}
