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
        
        AutograderController::grade($keys, $answers);
    }

    /**
     * Get grade from answer.
     *
     * @return grades
     */
    public function grade($keys, $answers) 
    {
        $results = [];
        for($i=0; $i<count($keys); $i++) {
            $key_temp = preg_split("/[)\s,(-]+/", $keys[$i]);
            $answer_temp = preg_split("/[)\s,(-]+/", $answers[$i]);

            $key = [];
            for($j=0; $j<count($key_temp); $j++) {
                if (!empty($key_temp[$j])) {
                    $key[] = $key_temp[$j];
                }
            }

            $answer = [];
            for($j=0; $j<count($answer_temp); $j++) {
                if (!empty($answer_temp[$j])) {
                    $answer[] = $answer_temp[$j];
                }
            }

            $results[] = AutograderController::jaccardIndex($key, $answer);
        }

        foreach($results as $result) {
            echo $result . " <br/> ";
        }
    }

    /**
     * Get Jaccard Index score.
     *
     * @return score
     */
    public function jaccardIndex($key, $answer) {
        $arr_intersection = count(array_intersect( $key, $answer ));
        $arr_union = count(array_merge( $key, $answer )) - $arr_intersection;
        $jaccard_index = $arr_intersection / $arr_union;

        return $jaccard_index;
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
        $item1 = [];
        $item2 = [];

        $item1[] = '=SUM';
        $item1[] = 'A1';
        $item1[] = 'A2';
        $item1[] = 'A3';

        $item2[] = '=SUM';
        $item2[] = 'A8';
        $item2[] = 'A2';
        $item2[] = 'A1';

        $arr_intersection = count(array_intersect( $item1, $item2 ));
        $arr_union = count(array_merge( $item1, $item2 )) - $arr_intersection;
        $coefficient = $arr_intersection / $arr_union;

        echo "koefisien = " . $coefficient;
    }
}
