<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $cells_temp = [];
        $keys_formula = [];
        $keys_value = [];
        foreach($answer_keys as $answer_key) {
            $cells[] = 'Sheet1!' . $answer_key->cell;
            $cells_temp[] = $answer_key->cell;
            $keys_formula[] = $answer_key->formula;
            $keys_value[] = $answer_key->value;
        }

        $answers_formula = AutograderController::getStudentAnswer($request->id_spreadsheet, $cells, 0);
        $answers_value = AutograderController::getStudentAnswer($request->id_spreadsheet, $cells, 1);
        
        $results_formula = AutograderController::gradeFormula($keys_formula, $answers_formula);
        $results_value = AutograderController::gradeFormula($keys_value, $answers_value);
        $results = [];
        for ($i=0; $i<count($results_formula); $i++) {
            $results[] = ($results_formula[$i] + $results_value[$i])/2;
        }

        echo '
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Cell</th>
                    <th scope="col">Formula Kunci</th>
                    <th scope="col">Formula Jawaban</th>
                    <th scope="col">Nilai Kunci</th>
                    <th scope="col">Nilai Jawaban</th>
                    <th scope="col">Skor</th>
                </tr>
            </thead>
            <tbody>             
        ';

        $score = 0;
        for ($i=0; $i<count($results); $i++) {
            $score = $score + $results[$i]*100;
            echo '<tr>';
            echo '<th>' . $cells_temp[$i] . '</th>';
            echo '<td>' . $keys_formula[$i] . '</td>';
            echo '<td>' . $answers_formula[$i] . '</td>';
            echo '<td>' . $keys_value[$i] . '</td>';
            echo '<td>' . $answers_value[$i] . '</td>';
            echo '<td>' . $results[$i]*100 . '/100</td>';
            echo '</tr>';
        }
        echo '
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th class="table-primary">Skor Akhir</th>
                        <th class="table-primary">' . $score/count($results) . '</th>
                    </tr>
                </tbody>
            </table>
            <a href="/course/' . $id_course . '" style="float: right;" class="btn btn-primary" role="button">Kembali ke Kelas</a>
        ';

        DB::table('grades')->where([
            ['id_topic', '=' ,$id_topic], 
            ['id_user', '=', Auth::id()]            
        ])->delete();

        DB::table('grades')->insert([
            'id_course' => $id_course,
            'id_user' => Auth::id(),
            'id_topic' => $id_topic,
            'grade' => $score/count($results)
        ]);
    }

    /**
     * Get grade from answer value
     *
     * @return grades
     */
    public function gradeValue($keys, $answers) 
    {
        $results = [];
        for($i=0; $i<count($keys); $i++) {
            if ($keys[$i] == $answers[$i]) {
                $results[] = 1;
            } else {
                $results[] = 0;
            }
        }

        return $results;
    }

    /**
     * Get grade from answer formula
     *
     * @return grades
     */
    public function gradeFormula($keys, $answers) 
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

        return $results;
    }

    /**
     * Get Jaccard Index score
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
     * Get data from spreadsheet
     *
     * @return answer
     */
    public function getStudentAnswer($id_spreadsheet, $ranges, $type) 
    {
        $client = new Google_Client();
        $client->setApplicationName('Datalearn');
        $client->setAuthConfig(__DIR__.'/credentials.json');
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');

        $service = new \Google_Service_Sheets($client);
        
        $render = 'FORMULA';
        if ($type == 1) {
            $render = 'FORMATTED_VALUE';
        }
        $responses = $service->spreadsheets_values->batchGet($id_spreadsheet, [
            'valueRenderOption' => $render,
            'dateTimeRenderOption' => 'SERIAL_NUMBER',
            'ranges' => $ranges
        ]);

        $answers = [];
        foreach ($responses->valueRanges as $response) {
            if ($response->values == NULL) {
                $answers[] = NULL;
            } else {
                $answers[] = strtoupper(strval(($response->values)[0][0]));
            }
        }

        return $answers;
    }
}
