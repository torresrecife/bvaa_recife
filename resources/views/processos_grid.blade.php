@extends('layouts.layout')

@section('content')
    <div class="col-md-12">
        <div class="tab-content" id="myTabContent" style="margin-top: 15px;">
            <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="grid-tab">
                {!! $grid !!}
            </div>
        </div>
    </div>
@endsection
