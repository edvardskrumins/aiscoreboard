@extends('main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Score board</h1>
            <table class="table">
            <thead>
            <tr>
            <th scope ="col"></th>
            <th scope ="col">Name</th>
            <th scope ="col">Score</th>
            </tr>
            </thead>
            <tbody>
            @for($i = 0; $i < sizeof($score_table_tested_only); $i++)

            <tr>

                <th scope="row">{{ $i+1 }}</th>

                <td>{{ $algorithm_names_by_id[$i][0] }}</td>

                <td>{{ $score_table_tested_only[$i][1] }}</td>

            </tr>

           @endfor
            </tbody>
            </table>
        </div>
    </div>
</div>

@endsection