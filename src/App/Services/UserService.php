<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
    public function __construct(private Database $db)
    {
    }

    public function isEmailTaken(string $email)
    {
        //verifica se la password già esiste
        $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email = :email ;",
            ['email' => $email]
        )->count();

        if ($emailCount > 0) {
            throw new ValidatorService(["email" => "Email already taken"]);
        }
    }

    public function create(array $formData)
    {
        // l'opzione "cost" aumenta o diminuisce le risorse necessareie, cioè aumenta o diminuisce il tempo impiegato per eseguire la funzione
        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ["cost" => 12]);

        $this->db->query(
            "INSERT INTO users(email,password,age,country,social_media_url) 
            VALUES (:email, :password, :age, :country, :url) ;",
            [
                'email' => $formData['email'],
                'password' => $password,
                'age' => $formData['age'],
                'country' => $formData['country'],
                'url' => $formData['socialMediaURL'],
            ]
        );
    }




    public function login(array $formData)
    {
        // legge e salva in un array associativo i dati dell'user dal database, se l'user non esiste ritorna NULL
        $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email ;",
            ['email' => $formData['email']]
        )->find();

        if (!$user) {
            throw new ValidationException(["email" => ["The user doesn't already exist!"]]);
        } else {
            // compara le password, se $user['password'] è nulla associa il valore ""
            $passwordMatch = password_verify($formData['password'], $user['password'] ?? "");


            if (!$passwordMatch) {
                throw new ValidationException(["password" => ["Invalid password for the user."]]);
            } else {
                // salva nella sessione solo l'id che identifica in modo univoco l'user
                $_SESSION['user'] = $user['id'];
            }
        }
    }
}
