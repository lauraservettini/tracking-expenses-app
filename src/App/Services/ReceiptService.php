<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use App\Config\Paths;

class ReceiptService
{
    public function __construct(private Database $db)
    {
    }

    public function validate(?array $fileInfo)
    {
        if (!$fileInfo || $fileInfo['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException([
                "receipt" => ["Failed to upload file"],

            ]);
        }

        // impostare la dimensione massima del file a 3MB
        $maxFileSizeMB = 3 * 1024 * 1024;

        if ($fileInfo['size'] > ($maxFileSizeMB)) {
            throw new ValidationException([
                "receipt" => ["Max size of 3MB"]
            ]);
        }

        // verifica il nome del file
        $originalFileNAme = $fileInfo['name'];

        if (!preg_match('/^[A-Za-z0-9\s._-]+$/', $originalFileNAme)) {
            throw new ValidationException([
                "receipt" => ["Invalid filename"]
            ]);
        }

        // verifica la validità rispetto al MIME type
        $clientMimeType = $fileInfo['type'];
        $allowedMimeTypes = [
            "image/png",
            "image/jpeg",
            "application/pdf"
        ];

        if (!in_array($clientMimeType, $allowedMimeTypes)) {
            throw new ValidationException([
                "receipt" => "Invalid type"
            ]);
        }
    }

    public function upload(array $fileInfo, int $transaction)
    {
        // crea un filename univoco
        $fileExtension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
        $newFilename = bin2hex(random_bytes(16)) . "." . $fileExtension;

        // salva il file in /storage/uploads
        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFilename;

        if (!move_uploaded_file($fileInfo['tmp_name'], $uploadPath)) {
            throw new ValidationException([
                "receipt" => ["Failed to upload file"]
            ]);
        }

        // salva il path nel db
        $this->db->query(
            "INSERT INTO receipts(transaction_id, original_filename, storage_filename, media_type)
            VALUES (:transaction_id, :original_filename, :storage_filename, :media_type);",
            [
                "transaction_id" => $transaction,
                "original_filename" => $fileInfo['name'],
                "storage_filename" => $newFilename,
                "media_type" => $fileInfo['type']
            ]
        );
    }

    public function getReceipt(string $id)
    {
        $receipt = $this->db->query(
            "SELECT * FROM receipts
            WHERE id= :id;",
            [
                "id" => $id
            ]
        )->find();

        return $receipt;
    }

    public function read(array $receipt)
    {
        // ricostruisce e verifica il filepath
        $filePath = Paths::STORAGE_UPLOADS . "/" . $receipt['storage_filename'];

        if (!file_exists($filePath)) {
            redirectTo("/");
        }

        // se il filepath esiste forze il browser ad effettuale il download
        header("Content-Disposistion: inline;filename={$receipt['original_filename']}");
        header("Content-Type: {$receipt['media_type']}");

        readfile($filePath);
    }

    public function delete(array $receipt)
    {
        // ricostruisce e verifica il filepath
        $filePath = Paths::STORAGE_UPLOADS . "/" . $receipt['storage_filename'];

        // cancella il file nel sistema
        unlink($filePath);

        //cancella il file nel database
        $this->db->query(
            "DELETE FROM receipts
            WHERE id = :id",
            [
                "id" => $receipt['id']
            ]
        );
    }
}
