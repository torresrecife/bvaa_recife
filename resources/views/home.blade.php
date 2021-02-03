@extends('layouts.app')
@section('content')
@php
    $planilhas_running = [
        'prejuridico' => ['Pré-juridico','secondary'],
        'ajuizar' => ['Ajuizar','secondary'],
        'distribuidos' => ['Distribuídos','info'],
        'liminares' => ['Liminares','primary'],
        'mandados' => ['Mandados','success'],
        'devolvidos' => ['Devolvidos','warning']
        ];

    $planilhas_general = [
        'gereral' => ['Geral','warning'],
        'located' => ['Localizados','danger'],
        'charter' => ['Alvarás','secondary'],
        'stock' => ['Estoque','primary'],
        'resumed' => ['Retomados','success'],
        'restrict' => ['Restrições','warning'],
        'risk' => ['Risco','info'],
        'closure' => ['Encerramento','warning']
        ];

    $planilhas_other = [
        'panel' => ['Painel Geral','outline-primary'],
        'neojur' => ['NEO Jur','outline-danger'],
        'neocob' => ['NEO Cob','outline-info'],
        'ars' => ['ARS','outline-warning'],
        'grd' => ['GRD','outline-success'],
        'pfacil' => ['Petição Fácil','outline-info']
        ];
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Esteira</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                @foreach($planilhas_running as $key => $values)
                                    <a href="{{$key}}" class="btn btn-{{$values[1]}}" style="margin: 5px">
                                        <div class="d-flex flex-column justify-content-center align-items-center" style="width: 90px;height: 80px;">
                                            {{$values[0]}}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Planilhas Gerais</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            @foreach($planilhas_general as $key => $values)
                                <a href="{{$key}}" class="btn btn-{{$values[1]}}" style="margin: 5px">
                                    <div class="d-flex flex-column justify-content-center align-items-center" style="width: 90px;height: 80px;">
                                        {{$values[0]}}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Outros links</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            @foreach($planilhas_other as $key => $values)
                                <a href="{{$key}}" class="btn btn-{{$values[1]}}" style="margin: 5px">
                                    <div class="d-flex flex-column justify-content-center align-items-center" style="width: 90px;height: 80px;">
                                        {{$values[0]}}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
