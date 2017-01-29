<?php

namespace RadDB\Http\Controllers;

use RadDB\Tester;
use RadDB\Machine;
use RadDB\TestDate;
use RadDB\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RadDB\Http\Requests\UpdateTestDateRequest;

class TestDateController extends Controller
{
    /**
      * Instantiate a new controller instance.
      *
      * @return void
      */
     public function __construct()
     {
         // Only use these methods with the auth middlware
         $this->middleware('auth')->only([
             'store',
             'update',
             'destroy',
             'storeSurveyReport',
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
     * Fetch the survey report path for a survey.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSurveyReportPath($id)
    {
        return TestDate::select('report_file_path')->findOrFail($id);
    }

    /**
     * Show a form for creating a new survey.
     * This method is called with an optional parameter $id which corresponds to
     * the machine ID the survey is being created for.
     * URI: /surveys/$id/create
     * Method: GET.
     *
     * @param int $machineId (optional)
     *
     * @return \Illuminate\Http\Response
     */
    public function create($machineId = null)
    {
        if (is_null($machineId)) {
            $machines = Machine::select('id', 'description')
                ->active()
                ->orderBy('description')
                ->get();
        } else {
            $machines = Machine::select('id', 'description')
                ->findOrFail($machineId);
        }

        return view('surveys.surveys_create', [
            'id'        => $machineId,
            'testers'   => Tester::get(),
            'testtypes' => TestType::get(),
            'machines'  => $machines,
        ]);
    }

    /**
     * Save survey data to the database
     * URI: /surveys
     * Method: POST.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateTestDateRequest $request)
    {
        // Check if action is allowed
        $this->authorize(TestDate::class);

        $testdate = new TestDate();

        $testdate->test_date = $request->test_date;
        $testdate->machine_id = $request->machineID;
        $testdate->tester1_id = $request->tester1ID;
        if (empty($request->tester2ID)) {
            $testdate->tester2_id = 10;
        } else {
            $testdate->tester2_id = $request->tester2ID;
        }
        $testdate->type_id = $request->test_type;
        if (! empty($request->notes)) {
            $testdate->notes = $request->notes;
        }
        if (! empty($request->accession)) {
            $testdate->accession = $request->accession;
        }

        if ($testdate->save()) {
            $message = 'Survey '.$testdate->id.' added.';
            Log::info($message);

            return redirect()->route('index')->with('success', 'Survey added');
        } else {
            return redirect()->route('index')->with('fail', 'Error adding survey');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show a form for editing a survey. Typically used to edit the survey date.
     * URI: /surveys/$id/edit
     * Method: Get.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($surveyId)
    {
        // Return survey information for $id
        return view('surveys.surveys_edit', [
            'survey'    => TestDate::findOrFail($surveyId),
            'machine'   => Machine::findOrFail($survey->machine_id),
            'tester1'   => Tester::findOrFail($survey->tester1_id),
            'tester2'   => Tester::find($survey->tester2_id),
            'testtype'  => TestType::find($survey->type_id),
            'testers'   => Tester::get(),
            'testtypes' => TestType::get(),
        ]);
    }

    /**
     * Update the survey info for $surveyId.
     * URI: /surveys/$surveyId
     * Method: PUT.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTestDateRequest $request, $surveyId)
    {
        // Check if action is allowed
        $this->authorize(TestDate::class);

        $testdate = TestDate::find($surveyId);

        if ($testdate->test_date != $request->test_date) {
            $testdate->test_date = $request->test_date;
        }
        if ($testdate->tester1_id != $request->tester1ID) {
            $testdate->tester1_id = $request->tester1ID;
        }
        if ($testdate->tester2_id != $request->tester2ID) {
            $testdate->tester2_id = $request->tester2ID;
        }
        if ($testdate->notes != $request->notes) {
            $testdate->notes = $request->notes;
        }
        if ($testdate->accession != $request->accession) {
            $testdate->accession = $request->accession;
        }

        if ($testdate->save()) {
            $message = 'Survey '.$testdate->id.' edited.';
            Log::info($message);

            return redirect()->route('index')->with('success', 'Survey edited');
        } else {
            return redirect()->route('index')->with('fail', 'Error editing survey');
        }
    }

    /**
     * Show a form for adding a new survey report.
     * URI: /surveys/addReport
     * Method: GET.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function addSurveyReport(Request $request)
    {
        // TODO: Have the survey ID as an optional parameter.
        // URI: /surveys/{surveyId?}/addReport
        // In the initial attempt, route matching failed when the survey ID
        // wasn't provided in the URI.

        return view('surveys.surveys_addReport');
    }

    /**
     * Handle an uploaded survey report
     * URI: /surveys/addReport
     * Method: PUT.
     *
     * @param int                      $surveyId
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeSurveyReport(StoreSurveyReport $request)
    {
        // Check if action is allowed
        $this->authorize(TestDate::class);

        $testdate = TestDate::find($request->surveyId);

        // Handle the uploaded file
        // This breaks the way service reports were handled in the previous version. Deal with it.
        if ($request->hasFile('surveyReport')) {
            $testdate->report_file_path = $request->surveyReport->store('public/SurveyReports');
        }

        if ($testdate->save()) {
            $message = 'Survey report for survey '.$testdate->id.' stored.';
            Log::info($message);

            return redirect()->route('index')->with('success', 'Survey report uploaded');
        } else {
            return redirect()->route('index')->with('fail', 'Error uploading survey report');
        }
    }

    /**
     * Not implemented. Should not be able to remove surveys from the database.
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
