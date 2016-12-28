<?php
namespace RadDB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use RadDB\Machine;
use RadDB\Tester;
use RadDB\TestType;
use RadDB\TestDate;
use RadDB\Http\Requests;

class TestDateController extends Controller
{

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
     * Fetch the survey report path for a survey
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSurveyReportPath($id)
    {
        $surveyReportPath = TestDate::select('report_file_path')->findOrFail($id);

        return $surveyReportPath;
    }

    /**
     * Show a form for creating a new survey.
     * This method is called with an optional parameter $id which corresponds to
     * the machine ID the survey is being created for.
     * URI: /surveys/$id/create
     * Method: GET
     *
     * @param int $machineId (optional)
     * @return \Illuminate\Http\Response
     */
    public function create($machineId = null)
    {
        $testers = Tester::select('id', 'initials')
            ->get();
        $testtypes = TestType::select('id', 'test_type')
            ->get();

        if (is_null($machineId)) {
            $machines = Machine::select('id', 'description')
                ->get();
        }
        else {
            $machines = Machine::select('id', 'description')
                ->findOrFail($machineId);
        }

        return view('surveys.surveys_create', [
            'id' => $machineId,
            'testers' => $testers,
            'testtypes' => $testtypes,
            'machines' => $machines
        ]);
    }

    /**
     * Save survey data to the database
     * URI: /surveys
     * Method: POST
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'machineID' => 'required|integer',
            'test_date' => 'date_format:Y-m-d|max:10',
            'tester1ID' => 'required|string|max:4',
            'tester2ID' => 'string|max:4',
            'test_type' => 'required|integer',
            'notes' => 'string|max:65535',
            'accession' => 'numeric'
        ]);

        $testdate = new TestDate;

        // If the test date or today checkbox are left empty, use the current date.
        // If they're both set for some reason, use the current date
        if (empty($request->test_date) || $request->today == "on") {
            $testdate->test_date = date('Y-m-d');
        }
        else {
            $testdate->test_date = $request->test_date;
        }

        $testdate->machine_id = $request->machineID;
        $testdate->tester1_id = $request->tester1ID;
        if (!empty($request->tester2ID)) {
            $testdate->tester2_id = $request->tester2ID;
        }
        else {
            $testdate->tester2_id = 0;
        }
        $testdate->type_id = $request->test_type;
        if (!empty($request->notes)) $testdate->notes = $request->notes;
        if (!empty($request->accession)) $testdate->accession = $request->accession;

        $testdate->save();

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show a form for editing a survey. Typically used to edit the survey date.
     * URI: /surveys/$id/edit
     * Method: Get
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($surveyId)
    {
        $testers = Tester::select('id', 'initials')
            ->get();
        $testtypes = TestType::select('id', 'test_type')
            ->get();

        // Retrieve survey information for $id
        $survey = TestDate::find($surveyId);
        $machine = Machine::find($survey->machine_id);
        $tester1 = Tester::find($survey->tester1_id);
        if ($survey->tester2_id <> 0) {
            $tester2 = Tester::find($survey->tester2_id);
        }
        else {
            $tester2 = null;
        }
        $testtype = TestType::find($survey->type_id);

        return view('surveys.surveys_edit', [
            'survey' => $survey,
            'machine' => $machine,
            'tester1' => $tester1,
            'tester2' => $tester2,
            'testtype' => $testtype,
            'testers' => $testers,
            'testtypes' => $testtypes
        ]);
    }

    /**
     * Update the survey info for $surveyId.
     * URI: /surveys/$surveyId
     * Method: PUT
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $surveyId)
    {
        $this->validate($request, [
            'id' => 'require|integer',
            'machineID' => 'required|integer',
            'test_date' => 'required|date_format:Y-m-d|max:10',
            'tester1ID' => 'required|string|max:4',
            'tester2ID' => 'string|max:4',
            'test_type' => 'required|integer',
            'notes' => 'string|max:65535',
            'accession' => 'numeric'
        ]);

        $survey = TestDate::find($surveyId);

        if ($survey->test_date <> $request->test_date) $survey->test_date = $request->test_date;
        if ($survey->tester1_id <> $request->tester1ID) $survey->tester1_id = $request->tester1ID;
        if ($survey->tester2_id <> $request->tester2ID) $survey->tester2_id = $request->tester2ID;
        if ($survey->notes <> $request->notes) $survey->notes = $request->notes;
        if ($survey->accession <> $request->accession) $survey->accession = $request->accession;

        $survey->save();

        return redirect('/');

    }

    /**
     * Show a form for adding a new survey report.
     * URI: /surveys/addReport
     * Method: GET
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addSurveyReport(Request $request)
    {
        return view('surveys.surveys_addReport');
    }

    /**
     * Handle an uploaded survey report
     * URI: /surveys/addReport
     * Method: PUT
     *
     * @param int $surveyId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeSurveyReport(Request $request)
    {
        $this->validate($request, [
            'surveyId' => 'required||exists:testdates,id|integer',
            'surveyReport' => 'required|file|mimes:pdf',
        ]);

        $survey = TestDate::find($request->surveyId);

        // Handle the uploaded file
        // Get the file name of the uploaded file
        $surveyReportName = $request->surveyReport->getClientOriginalName();

        // Service reports are stored in the storage/app/public/ServiceReports/$recResolveYr
        $path = "SurveyReports/" . date_parse($survey->test_date)['year'];
        if (!is_dir($path)) {
            Storage::makeDirectory($path);
        }

        if ($request->hasFile('surveyReport')) {
            $surveyReportPath = $request->surveyReport->storeAs($path, $surveyReportName);
            $survey->report_file_path = $surveyReportPath;
        }

        $survey->save();

        return redirect('/');
    }

    /**
     * Not implemented. Should not be able to remove surveys from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
