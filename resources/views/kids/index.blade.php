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
                    <a class="list-group-item list-group-item-action @if ($grade_id == 0) active @endif" href="{{ route('kids.index',['grade' => 0]) }}">全園児</a>
                    @foreach ($grades as $grade )
                    <a class="list-group-item list-group-item-action @if ($grade_id == $grade->id) active @endif" href="{{ route('kids.index',['grade' => $grade->id]) }}">{{ $grade->name }}</a>
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
                            <div class="accordion-body d-flex justify-content-around">
                                <div class="col-5">
                                    <div class="d-flex justify-content-around align-items-center border-bottom">
                                        <dt for="guardian" class="form-label p-0 m-0">保護者</dt>
                                        <button class="btn dropdown-toggle" type="button" id="guardianEditDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="guardianEditDropdown">
                                            <li><a href="#guardian-edit" class="dropdown-item" data-bs-toggle="modal" data-id="{{$kid->id}}">編集</a></li>
                                            <li><a href="{{ route('guardians.create',['kid' => $kid->id])}}" class="dropdown-item">追加</a></li>
                                        </ul>
                                    </div>
                                    @foreach ($kid->guardians as $guardian)
                                    <dd>{{$guardian->name}}</dd>
                                    @endforeach
                                </div>
                                <div class="col-5">
                                    <div class="d-flex justify-content-around align-items-center border-bottom">
                                        <dt for="guardian" class="form-label p-0 m-0">習い事</dt>
                                        <button class="btn dropdown-toggle" type="button" id="guardianEditDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </div>
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
    <!-- 保護者編集モーダル -->
    <div class="modal fade"  data-bs-backdrop="static" tabindex="-1" id="guardian-edit" role="dialog" aria-labelledby="guardian-show-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">保護者情報を修正する</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-responsive table-bordered">
                        <thead class="thead-default">
                            <tr>
                                <th class="col-3 text-center">続柄</th>
                                <th class="col-9 text-center">名前</th>
                            </tr>
                        </thead>
                        <tbody class="guardians">
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary guardian-update-btn">更新</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 保護者編集モーダル -->
    <!-- 保護者追加モーダル -->
    <!-- 習い事詳細モーダル -->
    <!-- 習い事編集モーダル -->
    <!-- 習い事削除モーダル -->
</div>
@endsection

