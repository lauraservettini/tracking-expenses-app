<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use Framework\TemplateEngine;

class UserService
{
    public function __construct(
        private Database $db,
        private TemplateEngine $view
    ) {
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

        // rigerenera il SESSION ID al signup
        session_regenerate_id();

        // salva nella sessione solo l'id che identifica in modo univoco l'user
        $_SESSION['user'] = $this->db->id();
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
                // rigerenera il SESSION ID ad ogni login
                session_regenerate_id();

                // salva nella sessione solo l'id che identifica in modo univoco l'user
                $_SESSION['user'] = $user['id'];

                // verifica se un utente è admin
                $isAdmin = (bool)$this->db->query(
                    "SELECT * FROM admins
                    WHERE user_id = :user_id;",
                    [
                        "user_id" => $_SESSION['user']
                    ]
                )->find();

                if (!empty($isAdmin)) {
                    // salva nella sessione isAdmin
                    $_SESSION['isAdmin'] = $isAdmin;

                    // salva nelle variabili globali isAdmin al login
                    $this->view->addGlobal("isAdmin", $_SESSION['isAdmin']);
                }
            }
        }
    }

    public function logout()
    {
        // cancella la variabile user dalla sessione
        // unset($_SESSION['user']);

        // distrugge tutti i dati della sessione, anche user a isAdmin
        session_destroy();

        // rigenera il SESSION ID
        // session_regenerate_id();

        // reimposta i valori del cookie
        $params = session_get_cookie_params();

        setcookie(
            "PHPSESSID",
            "",
            $params['lifetime'],
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
}
