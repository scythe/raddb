<!-- resources/views/index.blade.php -->

@extends('layouts.app')

@section('content')
<ul class="nav nav-pills mb-3" id="survey-pills-tab" role="tablist">
     <li class="nav-item" role="presentation">
         <button class="nav-link active" id="survey-pills-schedule" data-bs-toggle="pill" data-bs-target="#survey_schedule" type="button" role="tab" aria-controls="survey_schedule" aria-selected="true">Survey Schedule</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="survey-pills-pending" data-bs-toggle="pill" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="survey-pills-untested" data-bs-toggle="pill" data-bs-target="#untested" type="button" role="tab" aria-controls="untested" aria-selected="false">Survey Schedule</button>
     </li>
</ul>
<div class="tab-content" id="surveyTabs">
    <div role="tabpanel" class="tab-pane show active" id="survey_schedule" aria-labelledby="survey-pills-schedule">
        @include('inc.survey_schedule')
    </div>
    <div role="tabpanel" class="tab-pane" id="pending" aria-labelledby="survey-pills-pending">
        @include('inc.pending')
    </div>
    <div role="tabpanel" class="tab-pane" id="untested" aria-labelledby="survey-pills-untested">
        @include('inc.untested')
    </div>
</div>
@endsection
