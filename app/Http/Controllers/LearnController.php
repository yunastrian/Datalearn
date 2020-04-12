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
        $client = LearnController::getClient();
        $service = new \Google_Service_Drive($client);
        $copy = new \Google_Service_Drive_DriveFile();

        $response = $service->files->copy($id_spreadsheet, $copy);

        $permission_response = LearnController::edit_permission($response->id);

        $topic = DB::table('topics')->where('id_spreadsheet', $id_spreadsheet)->first();
        $content = $topic->content;
        return view('learn', ['id_spreadsheet' => $response->id, 'content' => $content]);
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

        $permission_response = LearnController::edit_permission($response->spreadsheetId);

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
     * Edit Permission.
     *
     * @return response
     */
    public function edit_permission($id)
    {
        $client = LearnController::getClient();
        $service = new \Google_Service_Drive($client);
        $permission = new \Google_Service_Drive_Permission([
            'role' => 'writer',
            'type' => 'anyone'
        ]);
        $response = $service->permissions->create($id, $permission);

        return $response;
    }

    /**
     * Edit Spreadsheet.
     *
     * @return view
     */
    public function edit($id_course, $id_spreadsheet)
    {
        $topic = DB::table('topics')->where('id_spreadsheet', $id_spreadsheet)->first();
        return view('edit', ['id_spreadsheet' => $id_spreadsheet, 'topic' => $topic]);
    }

    /**
     * Save Spreadsheet.
     *
     * @return message
     */
    public function save(Request $request)
    {
        echo $request->id_spreadsheet;
        echo $request->myTextArea;
        echo $request->richh;
        DB::table('topics')->insert([
            'id_course' => '2',
            'name' => 'ahaha',
            'content' => $request->richh,
            'id_spreadsheet' => $request->id_spreadsheet
        ]);
    }

    /**
     * Submit Spreadsheet.
     *
     * @return Score
     */
    public function submit(Request $request) 
    {
        $id = $request->id_spreadsheet;
        $client = LearnController::getClient();
        $service = new \Google_Service_Sheets($client);
 
        $ranges = [];
        $ranges[] = 'Sheet1!A1';
        $ranges[] = 'Sheet1!A2';
        $ranges[] = 'Sheet1!A3';
        $responses = $service->spreadsheets_values->batchGet($request->id_spreadsheet, [
            'valueRenderOption' => 'FORMULA',
            'dateTimeRenderOption' => 'SERIAL_NUMBER',
            'ranges' => $ranges
        ]);

        foreach ($responses->valueRanges as $response) {
            if ($response->values == NULL) {
                echo "Kosong \n";
            } else {
                echo '<pre>', var_export(strval(($response->values)[0][0]), true), '</pre>', "\n";
            }
        }
    }

    public function test()
    {
        $answer = DB::table('spreadsheets')->where('id', 1)->get();
        $j = [];
        foreach($answer as $a) {
            $j[] = 'Sheet1!' . $a->cell;
        }

        var_dump($j);

        $ranges = [];
        $ranges[] = 'Sheet1!A1';
        $ranges[] = 'Sheet1!A2';
        $ranges[] = 'Sheet1!A3';
        var_dump($ranges);
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
