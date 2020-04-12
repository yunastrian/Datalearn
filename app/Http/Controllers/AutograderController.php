<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Google_Client;

class AutograderController extends Controller
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
     * Submit Spreadsheet.
     *
     * @return Score
     */
    public function index($id_course, $id_topic, Request $request) 
    {
        $answer_keys = DB::table('spreadsheets')->where('id', $id_topic)->get();
        $cells = [];
        $keys = [];
        foreach($answer_keys as $answer_key) {
            $cells[] = 'Sheet1!' . $answer_key->cell;
            $keys[] = $answer_key->value;
        }
        
        $answers = AutograderController::getStudentAnswer($request->id_spreadsheet, $cells);
    }

    /**
     * Get data from spreadsheet.
     *
     * @return answer
     */
    public function getStudentAnswer($id_spreadsheet, $ranges) 
    {
        $client = new Google_Client();
        $client->setApplicationName('Datalearn');
        $client->setAuthConfig(__DIR__.'/credentials.json');
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');

        $service = new \Google_Service_Sheets($client);
 
        $responses = $service->spreadsheets_values->batchGet($id_spreadsheet, [
            'valueRenderOption' => 'FORMULA',
            'dateTimeRenderOption' => 'SERIAL_NUMBER',
            'ranges' => $ranges
        ]);

        $answers = [];
        foreach ($responses->valueRanges as $response) {
            if ($response->values == NULL) {
                $answers[] = NULL;
            } else {
                $answers[] = strval(($response->values)[0][0]);
            }
        }

        return $answers;
    }

    public function test()
    {
        $j = [];
        $j[] = 'haah';
        $j[] = NULL;
        $j[] = '=gg';

        foreach($j as $k) {
            echo $k . "\n";
        }
    }
}
