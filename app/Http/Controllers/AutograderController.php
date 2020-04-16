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
        $keys = [];
        foreach($answer_keys as $answer_key) {
            $cells[] = 'Sheet1!' . $answer_key->cell;
            $cells_temp[] = $answer_key->cell;
            $keys[] = $answer_key->value;
        }

        $answers = AutograderController::getStudentAnswer($request->id_spreadsheet, $cells);
        
        $results = AutograderController::grade($keys, $answers);

        echo '
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Cell</th>
                    <th scope="col">Kunci</th>
                    <th scope="col">Jawaban</th>
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
            echo '<td>' . $keys[$i] . '</td>';
            echo '<td>' . $answers[$i] . '</td>';
            echo '<td>' . $results[$i]*100 . '/100</td>';
            echo '</tr>';
        }
        echo '
                    <tr>
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

        return $results;
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
                $answers[] = strtoupper(strval(($response->values)[0][0]));
            }
        }

        return $answers;
    }

    public function test()
    {
        echo strtoupper("=sum(A1,A2)");
    }
}
