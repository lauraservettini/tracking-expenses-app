<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Dotenv\Exception\ValidationException;

class UserService
{
    public function __construct(private Database $db)
    {
    }

    public function isEmailTaken(string $email)
    {
        //verifica se la password già esiste
        $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email = :email",
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
}
