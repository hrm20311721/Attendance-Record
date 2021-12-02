@extends('layouts.app')

@section('content')
<div class="row">
    <!--　クラス一覧　-->
    <div class="mt-3 col-md-4">
        <div class="card">
            <div class="card-header">
                クラス一覧
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <a class="list-group-item list-group-item-action active" href="{{ route('grades.kids',['grade' => 0]) }}">全園児</a>
                    @foreach ($grades as $grade )
                    <a class="list-group-item list-group-item-action" href="{{ route('grades.kids',['grade' => $grade->id]) }}">{{ $grade->name }}</a>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <!--　園児一覧 -->
    <div class="mt-3 col-md-8">
        <div class="card">
            <div class="card-header">
                園児一覧
            </div>
            <div class="card-body">
                <div class="list-group accordion" id="accordion">
                    @foreach ($kids as $kid )
                    <div class="list-group-item list-group-item-action mb-0 acordion-item" id="heading{{$kid->id}}">
                        <h5 class="acordion-header mb-0" data-toggle="collapse" data-target="#detail{{$kid->id}}">{{ $kid->name }}</h5>
                    </div>
                    <div class="list-group-item mb-0 accordion-collapse collapse" aria-labelledby="heading{{$kid->id}}" data-parent="#accordion" id="detail{{$kid->id}}">
                        <div class="accordion-body">
                            <p for="guardian">保護者</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

