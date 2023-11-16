<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\AdminService;

class AdminController
{
    public function __construct(
        private TemplateEngine $view,
        private AdminService $adminService
    ) {
    }

    public function home()
    {
        // assegna il valore dal query parameter 'p', se non esiste assegna 1
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;

        // numero dei risultari da visionare per pagina
        $length = 3;

        // valore da cui partire negli indici della ricerca della query
        $offset = ($page - 1) * $length;

        // quando si ricerca una stringa prev ritorna alla pagina precedente alla ricerca
        $searchTerm = $_GET['s'] ?? null;

        [$transactions, $transactionCount] = $this->adminService->getAllTransactions(
            $length,
            $offset
        );

        // numero dell'ultima pagina relativa alle transazioni in funzione al numero di record visualizzati nella pagina
        $lastPage = ceil($transactionCount / $length);

        // array con il numero di pagina da 1 a $lastPage
        $pages = $lastPage ? range(1, $lastPage) : [];

        // genera un array con i link per ogni pagina da 1 a $lastPage
        $pageLinks = array_map(
            fn ($pageNum) => http_build_query([
                "p" => $pageNum,
                "s" => $searchTerm
            ]),
            $pages
        );

        echo $this->view->render(
            "/admin/dashboard.php",
            [
                "transactions" => $transactions,
                "currentPage" => $page,
                "previousPageQuery" => http_build_query(
                    [
                        "p" => $page - 1,
                        "s" => $searchTerm
                    ]
                ),
                "nextPageQuery" => http_build_query(
                    [
                        "p" => $page + 1,
                        "s" => $searchTerm
                    ]
                ),
                "lastPage" => $lastPage,
                "pageLinks" => $pageLinks,
                "searchTerm" => $searchTerm
            ]
        );
    }


    public function getUsers()
    {

        // assegna il valore dal query parameter 'p', se non esiste assegna 1
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;

        // numero dei risultari da visionare per pagina
        $length = 3;

        // valore da cui partire negli indici della ricerca della query
        $offset = ($page - 1) * $length;

        // quando si ricerca una stringa prev ritorna alla pagina precedente alla ricerca
        $searchTerm = $_GET['s'] ?? null;

        [$users, $usersCount] = $this->adminService->getAllUsers(
            $length,
            $offset
        );

        // numero dell'ultima pagina relativa alle transazioni in funzione al numero di record visualizzati nella pagina
        $lastPage = ceil($usersCount / $length);

        // array con il numero di pagina da 1 a $lastPage
        $pages = $lastPage ? range(1, $lastPage) : [];

        // genera un array con i link per ogni pagina da 1 a $lastPage
        $pageLinks = array_map(
            fn ($pageNum) => http_build_query([
                "p" => $pageNum,
                "s" => $searchTerm
            ]),
            $pages
        );

        echo $this->view->render(
            "/admin/dashboard.php",
            [
                "users" => $users,
                "currentPage" => $page,
                "previousPageQuery" => http_build_query(
                    [
                        "p" => $page - 1,
                        "s" => $searchTerm
                    ]
                ),
                "nextPageQuery" => http_build_query(
                    [
                        "p" => $page + 1,
                        "s" => $searchTerm
                    ]
                ),
                "lastPage" => $lastPage,
                "pageLinks" => $pageLinks,
                "searchTerm" => $searchTerm
            ]
        );
    }

    public function getUser(array $params)
    {
        // assegna id user da route params
        $userId = (int) $params['user'];

        // assegna il valore dal query parameter 'p', se non esiste assegna 1
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;

        // numero dei risultari da visionare per pagina
        $length = 3;

        // valore da cui partire negli indici della ricerca della query
        $offset = ($page - 1) * $length;

        // quando si ricerca una stringa prev ritorna alla pagina precedente alla ricerca
        $searchTerm = $_GET['s'] ?? null;

        [$transactions, $transactionCount] = $this->adminService->getUserTransactions(
            $length,
            $offset,
            $userId
        );

        // numero dell'ultima pagina relativa alle transazioni in funzione al numero di record visualizzati nella pagina
        $lastPage = ceil($transactionCount / $length);

        // array con il numero di pagina da 1 a $lastPage
        $pages = $lastPage ? range(1, $lastPage) : [];

        // genera un array con i link per ogni pagina da 1 a $lastPage
        $pageLinks = array_map(
            fn ($pageNum) => http_build_query([
                "p" => $pageNum,
                "s" => $searchTerm
            ]),
            $pages
        );

        echo $this->view->render(
            "/admin/user_details.php",
            [
                "transactions" => $transactions,
                "currentPage" => $page,
                "previousPageQuery" => http_build_query(
                    [
                        "p" => $page - 1,
                        "s" => $searchTerm
                    ]
                ),
                "nextPageQuery" => http_build_query(
                    [
                        "p" => $page + 1,
                        "s" => $searchTerm
                    ]
                ),
                "lastPage" => $lastPage,
                "pageLinks" => $pageLinks,
                "searchTerm" => $searchTerm
            ]
        );
    }
}
