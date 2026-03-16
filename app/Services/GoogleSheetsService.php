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
        $this->client->setAuthConfig(storage_path('app/institutoimport-a87b3220723c.json'));
        $this->client->addScope(Sheets::SPREADSHEETS_READONLY);
        $this->service = new Sheets($this->client);
    }

    public function getSheetData($spreadsheetId, $range) {
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        
        if (empty($values)) return [];

        // 1. Limpiamos la cabecera y contamos cuántas columnas DEBE haber
        $header = array_map('trim', $values[0]);
        $columnCount = count($header);
        
        $data = [];
        for ($i = 1; $i < count($values); $i++) {
            $row = $values[$i];

            $fullRow = array_slice(array_pad($row, $columnCount, ''), 0, $columnCount);

            $data[] = array_combine($header, $fullRow);
        }
        return $data;
    }

    public function writeSheetData($spreadsheetId, $range, $rows)
    {
        $values = [];

        foreach ($rows as $row) {
            $values[] = [
                $row['alumn'],
                $row['teacher'],
                $row['created_at'],
                $row['returned_at']
            ];
        }

        $body = new \Google\Service\Sheets\ValueRange([
            'values' => $values
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $this->service->spreadsheets_values->update(
            $spreadsheetId,
            $range,
            $body,
            $params
        );
    }
}
