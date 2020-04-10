<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function index($id_course, $id_spreadsheet)
    {
        return view('learn', ['id_spreadsheet' => $id_spreadsheet]);
    }

    /**
     * Create new Spreadsheet.
     *
     * @return id_spreadsheet
     */
    public function new($id_course, Request $request)
    {
        // Initialize
        $client = LearnController::getClient();
        $service = new \Google_Service_Sheets($client);

        // Create Spreadsheet
        $spreadsheet = new \Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => $request->topic_name
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
            $msg = 0;
        } else {
            $msg = 1;
            DB::table('topics')->insert([
                'id_course' => $id_course,
                'name' => $request->topic_name,
                'content' => 'Konten',
                'id_spreadsheet' => $response->spreadsheetId
            ]);
        }
        return redirect()->route('course', ['id_course' => $id_course, 'msg' => $msg]);
    }

    /**
     * Edit Spreadsheet.
     *
     * @return message
     */
    public function edit(Request $request)
    {
        echo 'htmlentities';
        echo htmlentities('_token=g3S3CcOUpXXaTOIdwsxCZzejdAlRzpFTGXoV5C5j&%E2%80%9DmyTextarea%E2%80%9D=%3Cp%3ENext%2C+use+our+Get+Started+docs+to+setup+Tiddny%21%3C%2Fp%3E');
        echo $request->Next;
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
