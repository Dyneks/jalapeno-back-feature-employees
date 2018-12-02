<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class CandidateController {
    private $ctx;

    public function __construct($ctx)
    {
        $this->ctx = $ctx;
    }
    
    public function get(Request $request, Response $response, array $args) {
        $stmt = $this->ctx->db->prepare('SELECT `id`, `vardas`, `pavardė`, `asmens_kodas`, `adresas`, `telefonas`, `paštas`, `patirtis`, `komentaras`, `išsilavinimas`, `gimimo_data`, `atrinktas_pokalbiui` FROM `kandidatai` WHERE id = :id;');
        $stmt->bindParam(':id', $args['id']);
        $stmt->execute();

        return $response->withJson($stmt->fetch());
    }
    
    public function create(Request $request, Response $response, array $args) {
        //TODO: validate
        $randomPass = bin2hex(random_bytes(8));
        $passwordHash = password_hash($randomPass, PASSWORD_BCRYPT);

        $params = $request->getParsedBody();

        $state = 0;
        
        $stmt = $this->ctx->db->prepare(
            'INSERT INTO kandidatai (
                `vardas`,
                `pavardė`,
                `asmens_kodas`,
                `adresas`,
                `telefonas`,
                `paštas`,
                `patirtis`,
                `komentaras`, 
                `išsilavinimas`,
                `gimimo_data`, 
                `atrinktas_pokalbiui`
                )
                VALUES (
                :firstName,
                :lastName,
                :IdentificationCode,
                :adress,
                :phone,
                :mail,
                :expierience,
                :comment,
                :education,
                :dob,
                :interview

            );');

        
        $stmt->bindParam(':firstName', $params['firstName']);
        $stmt->bindParam(':lastName', $params['lastName']);
        $stmt->bindParam(':IdentificationCode', $params['IdentificationCode']);
        $stmt->bindParam(':adress', $params['adress']);
        $stmt->bindParam(':phone', $params['phone']);
        $stmt->bindParam(':mail', $params['mail']);
        $stmt->bindParam(':expierience', $params['expierience']);
        $stmt->bindParam(':comment', $params['comment']);     
        $stmt->bindParam(':dob', $params['dob']);
        $stmt->bindParam(':interview', $state);
        $stmt->execute();

        $id = $this->ctx->db->lastInsertId();


        return $response->withJson(array("id" => (int)$id), 201);
    }
    
    public function invite(Request $request, Response $response, array $args) {
        //TODO: validate
        $stmt = $this->ctx->db->prepare('UPDATE kandidatai SET atrinktas_pokalbiui = :state WHERE id = :id');

        $state = 1;
        
        $stmt->bindParam(':id', $args['id']);
        $stmt->bindParam(':state', $state);
        $stmt->execute();
        
        /*$mg = Mailgun::create('key-d3055fc96350349d7adc5bd173e27e0c');

        $mg->messages()->send('sandbox491d9c6765054fca8a1c365e6f9c88c0.mailgun.org', [
            'from'    => 'noreply@jalapenopizza.lt',
            'to'      => $params['email'],
            'subject' => 'Jūs atrinktas į darbo pokalbį!',
            'text'    => 'Sveiki, jūs esate atrinktas į darbo pokalbį. Paskambinkite numeriu +37069001000, susitarti dėl laiko.'
          ]);
         */
        return $response->withStatus(204);
    }
    
}
