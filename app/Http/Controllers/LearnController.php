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
        echo 'nyampe1';
        $client = LearnController::getClient();
        $service = new \Google_Service_Sheets($client);

        echo 'nyampe 2';
        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => 'Testing'
            ]
        ]);
        echo 'nyampe 3';
        $response = $service->spreadsheets->create($spreadsheet, ['fields' => 'spreadsheetId']);
        echo 'nyampe';

        $data = array(
            'role' => 'owner',
            'type' => 'user',
            'emailAddress' => 'piscokn@gmail.com'
        );

        echo $response->spreadsheetId;
        $service2 = new \Google_Service_Drive($client);
        $permission2 = new \Google_Service_Drive_Permission([
            'role' => 'writer',
            'type' => 'user',
            'emailAddress' => 'piscokn@gmail.com'
        ]);
        // $permission2->setValue('piscokn@gmail.com');
        // $permission2->setType('user');
        // $permission2->setRole('owner');
        $response2 = $service2->permissions->create($response->spreadsheetId, $permission2);
        // # Create a connection
        // echo $response->spreadsheetId . '\n';
        // $url = 'https://www.googleapis.com/drive/v3/files/' . $response->spreadsheetId . '/permissions';
        // echo $url;
        // $ch = curl_init($url);
        // # Form data string
        // $postString = http_build_query($data, '', '&');
        // # Setting our options
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // # Get the response
        // $responsee = curl_exec($ch);
        // curl_close($ch);

        // echo $responsee;

        // return view('welcome');
        // printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
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
