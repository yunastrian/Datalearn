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
            echo '<td>' . number_format($results[$i]*100, 2, '.', ''). '</td>';
            echo '</tr>';
        }
        echo '
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th class="table-primary">Skor Akhir</th>
                        <th class="table-primary">' . number_format($score/count($results), 2, '.', '') . '</th>
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

            $flag = 0;
            $range_func = array("COUNTBLANK", "MDETERM", "MINVERSE", "SUMPRODUCT", "TRANSPOSE", "COLUMNS", "ROWS");
            foreach($range_func as $func) {
                if (in_array('=' . $func, $answer_temp)) {
                    $flag = 1;
                    break;
                }
            }

            $key = [];
            for($j=0; $j<count($key_temp); $j++) {
                if (!empty($key_temp[$j])) {
                    $key[] = $key_temp[$j];
                }
            }

            $answer = [];
            for($j=0; $j<count($answer_temp); $j++) {
                if (!empty($answer_temp[$j])) {
                    $array = str_split($answer_temp[$j]);
                    if (in_array(':', $array)) {
                        if ($flag == 0) {
                            $idx = strpos($answer_temp[$j], ":");
                            
                            $row_start_temp = '';
                            $column_start = 0;
                            for ($k=0; $k<$idx; $k++) {
                                if ($k == 0) {
                                    $column_start = ord($array[$k]);
                                } else {
                                    $row_start_temp .= $array[$k];
                                }
                            }
                            $row_start = intval($row_start_temp);
                
                            $row_end_temp = '';
                            $column_end = 0;
                            for ($k=$idx+1; $k<count($array); $k++) {
                                if ($k == $idx+1) {
                                    $column_end = ord($array[$k]);
                                } else {
                                    $row_end_temp .= $array[$k];
                                }
                            }
                            $row_end = intval($row_end_temp);
                
                            $new_arr = [];
                            for ($k=$column_start; $k<=$column_end; $k++) {
                                for ($l=$row_start; $l<=$row_end; $l++) {
                                    $new_arr[] = chr($k) . strval($l);
                                }
                            }
        
                            foreach($new_arr as $n) {
                                $answer[] = $n;
                            }
                        } else {
                            $answer[] = $answer_temp[$j];
                        }
                    } else {
                        $answer[] = $answer_temp[$j];
                    }
                }
            }

            $results[] = AutograderController::cosine($key, $answer);
        }

        return $results;
    }

    /**
     * Get Cosine Similarity
     *
     * @return score
     */
    public function cosine($key, $answer) {
        if (count($answer) == 0) {
            return 0;
        } else {
            $token = [];
            $vector1 = [];
            $vector2 = [];
            foreach($key as $k) {
                if (!in_array($k, $token)) {
                    $token[] = $k;
                    $vector1[] = 0;
                    $vector2[] = 0;
                }
            }

            foreach($answer as $k) {
                if (!in_array($k, $token)) {
                    $token[] = $k;
                    $vector1[] = 0;
                    $vector2[] = 0;
                }
            }

            foreach($key as $k) {
                $vector1[array_search($k, $token)] += 1;
            }
            foreach($answer as $k) {
                $vector2[array_search($k, $token)] += 1;
            }

            $dot_product = 0;
            for($i=0; $i<count($token); $i++) {
                $dot_product += ($vector1[$i])*($vector2[$i]);
            }

            $length1 = 0;
            $length2 = 0;

            for($i=0; $i<count($token); $i++) {
                $length1 += pow($vector1[$i], 2);
                $length2 += pow($vector2[$i], 2);
            }

            $similarity = $dot_product/sqrt($length1*$length2);
            
            return $similarity;
        }
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
