@extends('main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @guest
            <div class="row justify-content-center">
               <h3 class="text-center">

                Mediju algoritmu testēšanas vide ir kvalifikācijas darba ietvaros izstrādāts produkts,
                kura pamatfunkcija ir iesūtīto algoritmu reklāmu šķirošanas pa reklāmas pauzēm efektivitātes novērtēšana.
                Vēl viena no sistēmas galvenajām funkcijām ir testa datu ģenerēšana. Testa dati ir attiecīgi reklāmu saraksts
                un reklāmas paužu grafiks. Katrai  reklāmai ir piešķirtas parametru vērtības, kas definē, kas ir reklāmas mērķauditorija.
                Parametru vērtības ir piešķirtas arī
                katrai reklāmas pauzei, kas definē, kas ir potenciālā auditorija, kas reklāmu noteiktajā laikā varētu skatīties.
               </h3>
            </div>
        @else
            <div class="col-md-8">
                <h1>TOP 10 labākie algoritmi</h1>
                <table class="table">
                <thead>
                <tr>
                <th scope ="col"></th>
                <th scope ="col">Nosaukums</th>
                <th scope ="col">Autors</th>
                <th scope ="col">Rezultāts</th>
                <th scope ="col">Testēšanas laiks</th>

                </tr>
                </thead>
                <tbody>
                @for($i = 0; $i < sizeof($score_table_tested_only); $i++)

                <tr>

                    <th scope="row">{{ $i+1 }}</th>

                    <td>{{ $algorithm_names_by_id[$i][0] }}</td>

                    @if(isset($algorithm_names_by_id[$i][1]->name))
                        <td>{{ $algorithm_names_by_id[$i][1]->name }}</td>
                    @else
                        <td>anonymous</td>
                    @endif
                    <td>{{ $score_table_tested_only[$i][1] }}</td>

                    <td>{{ $score_table_tested_only[$i][2] }}</td>


                </tr>

               @endfor
                </tbody>
                </table>
            </div>
        @endguest
    </div>
</div>

@endsection