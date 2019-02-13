@extends("main")
@section("content")
    <script src="/js/dataForm.js"></script>
    <div class="row">
        <h2 class="col s6 offset-s3">Available test data entries</h2>
    </div>
    <div class="row">
        <h5 class="col s6 offset-s3">Generate and download datasets</h5>
    </div>
    <div class="row">
        <ul class="col s4 offset-s4">
        @if (sizeof($data_entries))
            @foreach ($data_entries as $data_entry)
                <li>
                    <h3>{{ $data_entry->name }}</h3>
                    <a href="/data/{{ $data_entry->id }}/download/slots" class="waves-effect waves-light btn blue">Download Slots</a>
                    <a href="/data/{{ $data_entry->id }}/download/ads" class="waves-effect waves-light btn blue">Download Ads</a>
                    <a href="/data/{{ $data_entry->id }}/delete" class="waves-effect waves-light btn red">Delete</a>
                </li>
                <hr>
            @endforeach
        @else
            <h3>No entries available</h3>
        @endif
        </ul>
    </div>
    <div class="row">
        <h2 class="col s6 offset-s3">Add new Test data entry</h2>
    </div>
    <div class="row">
        <form class="col s6 offset-s3" id="dataForm" action="/data/generate/" method="post">
            {{ csrf_field() }}
            <div class="input-field col s12">
                <label for="testName">Name</label>
                <input type="text" id="testName" name="testName" class="validate" required maxlength="255">
            </div>
            <div class="input-field col s12">
                <label for="daySlotLen">Total slot length per day (seconds)</label>
                <input type="number" id="daySlotLen" class="validate" name="daySlotLen" min="1" required>
            </div>
            <div class="input-field col s6">
                <label for="dayFrom">Start generating from</label>
                <input type="text" id="dayFrom" class="validate datepicker" name="dayFrom" required>
            </div>
            <div class="input-field col s6">
                <label for="dayTill">Generate till</label>
                <input type="text" id="dayTill" class="validate datepicker" name="dayTill" required>
            </div>
            <div class="input-field col s6">
                <label for="adsSlotsRatio">Ads/slots ratio</label>
                <input type="number" id="adsSlotsRatio" class="validate" name="adsSlotsRatio" required min="2" step="0.01">
            </div>
            <!-- LATER
            <div class="input-field col s6">
                <label for="noAdsFrom">No ads from</label>
                <input type="time" id="noAdsFrom" class="validate" name="noAdsFrom" required>
            </div>
            <div class="input-field col s6">
                <label for="noAdsTill">No ads till</label>
                <input type="time" id="noAdsTill" class="validate" name="noAdsTill" required>
            </div>
            <div class="input-field col s6">
                <label for="minSlotLen">Minimum slot length</label>
                <input type="number" id="minSlotLen" class="validate" name="minSlotLen">
            </div>
            <div class="input-field col s6">
                <label for="maxSlotLen">Maximum slot length</label>
                <input type="number" id="maxSlotLen" class="validate" name="maxSlotLen">
            </div>-->
            <input type="submit" class="btn col s12" value="Save">
        </form>
    </div>
@endsection
