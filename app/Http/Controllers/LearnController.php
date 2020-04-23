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
    public function index($id_course, $id_topic)
    {
        $topic = DB::table('topics')->where('id', $id_topic)->first();
        $cells = DB::table('spreadsheets')->where('id', $id_topic)->get();
        $ranges = [];
        foreach($cells as $cell) {
            $ranges[] = 'Sheet1!' . $cell->cell;
        }
        $content = $topic->content;

        $client = LearnController::getClient();
        $service = new \Google_Service_Drive($client);
        $copy = new \Google_Service_Drive_DriveFile();

        $response = $service->files->copy($topic->id_spreadsheet, $copy);

        $permission_response = LearnController::edit_permission($response->id);
        
        // Clear Answer
        $requestBody = new \Google_Service_Sheets_BatchClearValuesRequest([
            'ranges' => $ranges
        ]);
        
        $service2 = new \Google_Service_Sheets($client);
        $response2 = $service2->spreadsheets_values->batchClear($response->id, $requestBody);

        return view('learn', ['topic_name' => $topic->name, 'id_course' => $id_course, 'id_spreadsheet' => $response->id, 'content' => $content]);
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
                'description' => $request->topic_description,
                'content' => '',
                'id_spreadsheet' => $response->spreadsheetId
            ]);
        }
        return redirect()->route('course', ['id_course' => $id_course, 'msg' => $msg]);
    }

    /**
     * Delete Topic.
     *
     * @return msg
     */
    public function delete($id_course, $id_topic)
    {
        DB::table('spreadsheets')->where('id', $id_topic)->delete();
        DB::table('grades')->where('id_topic', $id_topic)->delete();
        DB::table('topics')->where('id', $id_topic)->delete();
        
        return redirect()->route('course', ['id_course' => $id_course, 'msg' => 3]);
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
    public function edit($id_course, $id_topic)
    {
        $topic = DB::table('topics')->where('id', $id_topic)->first();
        $cells = DB::table('spreadsheets')->where('id', $id_topic)->get();

        return view('edit', ['cells' => $cells, 'id_course' => $id_course, 'id_spreadsheet' => $topic->id_spreadsheet, 'topic' => $topic]);
    }

    /**
     * Save Spreadsheet.
     *
     * @return message
     */
    public function save($id_course, $id_topic, Request $request)
    {
        $cells = [];
        foreach ($request->cells as $cell) {
            $cells[] = strtoupper($cell);
        }

        $client = LearnController::getClient();
        $service = new \Google_Service_Sheets($client);

        // Get Answer Formula
        $responses = $service->spreadsheets_values->batchGet($request->id_spreadsheet, [
            'valueRenderOption' => 'FORMULA',
            'dateTimeRenderOption' => 'SERIAL_NUMBER',
            'ranges' => $cells
        ]);

        $answers = [];
        foreach ($responses->valueRanges as $response) {
            if ($response->values == NULL) {
                $answers[] = NULL;
            } else {
                $answers[] = strtoupper(strval(($response->values)[0][0]));
            }
        }

        // Get Answer Value
        $responses2 = $service->spreadsheets_values->batchGet($request->id_spreadsheet, [
            'valueRenderOption' => 'FORMATTED_VALUE',
            'dateTimeRenderOption' => 'SERIAL_NUMBER',
            'ranges' => $cells
        ]);

        $answers2 = [];
        foreach ($responses2->valueRanges as $response) {
            if ($response->values == NULL) {
                $answers2[] = NULL;
            } else {
                $answers2[] = strtoupper(strval(($response->values)[0][0]));
            }
        }

        // Save to Database
        DB::table('spreadsheets')->where('id',$id_topic)->delete();
        for ($i=0; $i<count($answers); $i++) {
            DB::table('spreadsheets')->insert([
                'id' => $id_topic,
                'cell' => $cells[$i],
                'value' => $answers2[$i],
                'formula' => $answers[$i]
            ]);   
        }

        DB::table('topics')->where('id', $id_topic)->update([
            'content' => $request->rich_text,
        ]);

        return redirect()->route('course', ['id_course' => $id_course, 'msg' => 2]);
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
