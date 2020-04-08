<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;

class LearnController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
    }

    /**
     * Create new Spreadsheet.
     *
     * @return id_spreadsheet
     */
    public function new()
    {
        // Initialize
        $client = LearnController::getClient();
        $service = new \Google_Service_Sheets($client);

        // Create Spreadsheet
        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => 'Testing'
            ]
        ]);
        $response = $service->spreadsheets->create($spreadsheet, ['fields' => 'spreadsheetId']);

        // Edit permission
        $service2 = new \Google_Service_Drive($client);
        $permission2 = new \Google_Service_Drive_Permission([
            'role' => 'writer',
            'type' => 'anyone'
        ]);
        $response2 = $service2->permissions->create($response->spreadsheetId, $permission2);

        if (empty($response->spreadsheetId)) {
            echo "Pembuatan Gagal";
        } else {
            return $response->spreadsheetId;
        }
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Datalearn');
        $client->setAuthConfig(__DIR__.'/credentials.json');
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);
        $client->addScope(\Google_Service_Sheets::DRIVE);
        $client->setAccessType('offline');

        return $client;
    }
}
