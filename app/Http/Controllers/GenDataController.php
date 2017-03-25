<?php

namespace RadDB\Http\Controllers;

use RadDB\GenData;
use RadDB\Machine;
use RadDB\TestDate;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GenDataController extends Controller
{
    const LINEARITY = 0b0001;
    const ACCURACY = 0b0010;
    const BEAMQUAL = 0b0100;
    const REPRO = 0b1000;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Only apply auth middleware to these methods
        $this->middleware('auth')->only([
         'create',
         'store',
         'edit',
         'update',
         'destroy',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form to upload a spreadsheet for processing.
     * @param int $surveyId
     *
     * @return \Illuminate\Http\Response
     */
    public function create($surveyId)
    {
        $survey = TestDate::find($surveyId);
        $machine = Machine::find($survey->machine_id);
        $tubes = $machine->tube;

        return view('surveydata.generator.gendata_create', [
            'survey' => $survey,
            'machine' => $machine,
            'tubes' => $tubes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tube' => 'exists:tubes,id',
            'generatorData' => 'required|string',
        ]);

        // Process the data pasted into the form
        // Data comes from cells AA688:BB747 of the generator data spreadsheet
        // Convert the data into an array
        $genDataArray = explode("\n", $request->generatorData);

        foreach ($genDataArray as $genDataRow) {
            // Split each row into an array with each element
            // containing a column of data after rtrim()ming any trailing spaces
            // or newlines.
            $genDataCol = explode("\t", rtrim($genDataRow));

            // Only columns 1-4,6,8,17-19,21,24-28 are used.
            // Columns 1-4,6,8 are the set values
            $genData = new GenData();
            $genData->survey_id = $request->surveyId;
            $genData->tube_id = $request->tubeId;
            $genData->kv_set = $genDataCol[0];
            $genData->ma_set = $genDataCol[1];
            $genData->time_set = $genDataCol[2];
            $genData->mas_set = $genDataCol[3];
            $genData->add_filt = $genDataCol[5];
            $genData->distance = $genDataCol[7];

            // Take the linearity, accuracy, beam quality and reproducibility flags
            // from the table and pack it all into one byte
            // bit 0 - linearity
            // bit 1 - accuracy
            // bit 2 - beam quality
            // bit 3 - reproducibility
            //
            // Columns 17-19,21 contain 1 if the current row is used for that
            // particular measurement, and 0 if it isn't.
             $genData->use_flags = (($genDataCol[16] ? self::LINEARITY : 0) |
                                   ($genDataCol[17] ? self::ACCURACY : 0) |
                                   ($genDataCol[18] ? self::BEAMQUAL : 0) |
                                   ($genDataCol[20] ? self::REPRO : 0));

            // Columns 24-28 contain the actual measurements.
            // If there is no value, then store null
            $genData->kv_avg = empty($genDataCol[23]) ? null : $genDataCol[23];
            $genData->kv_max = empty($genDataCol[24]) ? null : $genDataCol[24];
            $genData->kv_eff = empty($genDataCol[25]) ? null : $genDataCol[25];
            $genData->exp_time = empty($genDataCol[26]) ? null : $genDataCol[26];
            $genData->exposure = empty($genDataCol[27]) ? null : $genDataCol[27];

            // Store the data
            $genData->save();
        }

        return redirect()->route('index');
    }

    /**
     * Process a spreadsheet file
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function processSpreadsheet(Request $request)
    {

    }

    /**
     * Show generator check data for a specific survey.
     *
     * @param int $surveyId
     *
     * @return \Illuminate\Http\Response
     */
    public function show($surveyId)
    {
        // Retrieve the generator test data
        $genData = GenData::where('survey_id', $surveyId)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
