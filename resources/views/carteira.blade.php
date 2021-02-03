@extends('layouts.app')
@section('content')
@php
    $carteira = $data['carteira'];
    $planilha = $data['planilha'];
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Carteira</div>
                <div class="card-body">
                    @foreach($carteira as $key => $values)
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                    <a href="{{$planilha}}/{{$values->bank_code}}" class="btn btn-info btn-lg" style="margin: 5px; width:50%">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            {{$values->bank_code}}
                                        </div>
                                    </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
