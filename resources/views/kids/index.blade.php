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
                    <a class="list-group-item list-group-item-action active" href="{{ route('kids.index',['grade' => 0]) }}">全園児</a>
                    @foreach ($grades as $grade )
                    <a class="list-group-item list-group-item-action" href="{{ route('kids.index',['grade' => $grade->id]) }}">{{ $grade->name }}</a>
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
                <div class="accordion" id="accordion">
                    @foreach ($kids as $kid )
                    <div class="mb-0 accordion-item">
                        <h5 class="accordion-header mb-0" id="heading{{$kid->id}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#detail{{$kid->id}} " aria-expanded="false" aria-controls="detail{{$kid->id}}">
                                {{ $kid->name }}
                            </button>
                        </h5>
                        <div id="detail{{$kid->id}}" class="mb-0 accordion-collapse collapse" aria-labelledby="heading{{$kid->id}}" data-bs-parent="#accordion">
                            <div class="accordion-body d-flex justify-content-between">
                                <div class="col-md-5">
                                    <dt for="guardian" class="form-label">保護者</dt>
                                    @foreach ($kid->guardians as $guardian)
                                    <dd>{{$guardian->name}}</dd>
                                    @endforeach
                                </div>
                                <div class="col-md-5">
                                    <dt for="guardian" class="form-label">習い事</dt>
                                    @foreach ($kid->lessons as $lesson)
                                    <dd>{{$lesson->name}}</dd>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

