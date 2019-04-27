@extends('main')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.1/css/mdb.min.css" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>            
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.0.0/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/locales/bootstrap-datepicker.de.min.js"></script> 


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.1/js/mdb.min.js"></script>
<div class="container">
<div class="row">
        <h2 class="text-center">Add new Test data entry</h2>
        <hr>
    </div>
    <!-- <div class="form-group"> -->
    <div class="col-md-5 col-md-offset-4">
        <form  id="dataForm" action="/data/generate/" method="post">
            {{ csrf_field() }}
            
            <div class="input-field input-group input-group-lg">
                <span class="input-group-addon" id="sizing-addon1">Name</span>
                <input type="text" name="testName" class="form-control" class="validate" required maxlength="255" aria-describedby="sizing-addon1" required>
            </div>
            

            <!-- <div class="input-field col-md-10">
                <label for="testName">Name</label>
                <input type="text" id="testName" name="testName" class="validate" required maxlength="255">
            </div> -->
            <div class="input-field input-group input-group-lg">
                <span class="input-group-addon" id="sizing-addon1">Total slot length per day (seconds)</span>
                <input type="text" name="daySlotLen" id="daySlotLen" min="1" class="form-control validate" aria-describedby="sizing-addon1" required>
            </div>
            <!-- <div class="input-field col-md-8">
                <label for="daySlotLen">Total slot length per day (seconds)</label>
                <input type="number" id="daySlotLen" class="validate" name="daySlotLen" min="1" required>
            </div> -->
            <div class="input-field input-group input-group-lg date_picker">
                <span class="input-group-addon" id="sizing-addon1">Start generating from</span>
                <input type="text" name="dayFrom" id="dayFrom" class="form-control validate datepicker" aria-describedby="sizing-addon1" required>
            </div>
            <!-- <div class="input-field col-md-10">
                <label for="dayFrom">Start generating from</label>
                <input type="text" id="dayFrom" class="validate datepicker" name="dayFrom" required>
            </div> -->
            <div class="input-field input-group input-group-lg date_picker">
                <span class="input-group-addon" id="sizing-addon1">Generate till</span>
                <input type="text" name="dayTill" id="dayTill" class="form-control validate datepicker" aria-describedby="sizing-addon1" required>
            </div>

            <!-- <div class="input-field col-md-10">
                <label for="dayTill">Generate till</label>
                <input type="text" id="dayTill" class="validate datepicker" name="dayTill" required>
            </div> -->
            <div class="input-field input-group input-group-lg">
                <span class="input-group-addon" id="sizing-addon1">Ads/slots ratio</span>
                <input type="number" name="adsSlotsRatio" id="adsSlotsRatio" class="form-control validate" required_min="2" step="0.01" aria-describedby="sizing-addon1" required>
            </div>
            

            <!-- <div class="input-field col-md-10">
                <label for="adsSlotsRatio">Ads/slots ratio</label>
                <input type="number" id="adsSlotsRatio" class="validate" name="adsSlotsRatio" required min="2" step="0.01">
            </div> -->
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
            <hr>
            <div class="row">
            <input type="submit" class="col-md-4 col-md-offset-4" value="Save">
            </div>
            
        </form>
        </div>
    </div>
    <!-- </div> -->
</div>


<script type="text/javascript">
$(function(){
        $('.date_picker input').datepicker({
           format: "dd/mm/yyyy",
           todayBtn: "linked",
           language: "en"
        });
    });
    </script>

@endsection