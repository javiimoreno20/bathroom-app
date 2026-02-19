<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/laravel-google.json'));
        $this->client->addScope(Sheets::SPREADSHEETS_READONLY);
        $this->service = new Sheets($this->client);
    }

    public function getSheetData($spreadsheetId, $range)
    {
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        if (empty($values)) return [];
        // Convertimos la primera fila en headers
        $header = array_map('trim', $values[0]);
        $data = [];
        for ($i = 1; $i < count($values); $i++) {
            $row = $values[$i];
            $data[] = array_combine($header, $row);
        }
        return $data;
    }
}
