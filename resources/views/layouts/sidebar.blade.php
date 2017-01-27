<nav class="navbar navbar-default navbar-static-top">
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav nav-tabs">
            <li class="active"><a href="{{ route('index') }}">Home</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dashboards<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('dashboard.dashboard')}}">Survey status</a></li>
                    <li><a href="{{ route('dashboard.showUntested')}}">Surveys to be scheduled</a></li>
                    <li><a href="{{ route('dashboard.showPending')}}">Pending surveys</a></li>
                    <li><a href="{{ route('dashboard.showSchedule')}}">Survey schedule</a></li>
                    <li><a href="{{ route('dashboard.surveyGraph') }}">Survey graphs</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Listings<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('machines.index') }}">List machines</a></li>
                    <li><a href="{{ route('modalities.showModalityIndex') }}">List by modality</a></li>
                    <li><a href="{{ route('locations.showLocationIndex') }}">List by location</a></li>
                    <li><a href="{{ route('manufacturers.showManufacturerIndex') }}">List by manufacturer</a></li>
                </ul>
            </li>
            @if (Auth::check())
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Machines<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('machines.create') }}">New machine</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Surveys<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('surveys.create') }}">Add survey</a></li>
                    <li><a href="{{ route('recommendations.create') }}">Add survey recommendation</a></li>
                    <li><a href="{{ route('surveys.addSurveyReport') }}">Add survey report</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Test Equipment<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('testgear.index') }}">Test Equipment</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{route('locations.index')}}">Locations</a></li>
                    <li><a href="{{route('manufacturers.index')}}">Manufacturers</a></li>
                    <li><a href="{{route('modalities.index')}}">Modalities</a></li>
                    <li><a href="{{route('testers.index')}}">Testers</a></li>
                    <li><a href="{{route('testtypes.index')}}">Test Types</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ url('/logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
            @else
            <li><a href="{{route('home.index')}}">Login</a></li>
            @endif

        </ul>
    </div>
</nav>
