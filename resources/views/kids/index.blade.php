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
                        <!-- accordion header -->
                        <h5 class="accordion-header mb-0" id="heading{{$kid->id}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#detail{{$kid->id}} " aria-expanded="false" aria-controls="detail{{$kid->id}}">
                                {{ $kid->name }}
                            </button>
                        </h5>
                        <!-- accordion contents -->
                        <div id="detail{{$kid->id}}" class="mb-0 accordion-collapse collapse" aria-labelledby="heading{{$kid->id}}" data-bs-parent="#accordion">
                            <div class="accordion-body d-flex justify-content-between justify-content-lg-around p-2">
                                <!-- 保護者 -->
                                <div class="col-6 m-0">
                                    <div class="text-center border-bottom d-flex justify-content-around align-items-center">
                                        <dt for="guardian" class="form-label p-0 m-0">保護者</dt>
                                        <!--　追加モーダル表示ボタン -->
                                        <a href="#guardian-create" class="btn bi bi-plus-square btn-outline-secondary border-0 bg-transparent p-0" data-bs-target="#guardian-create" type="button" data-bs-toggle="modal" data-id="{{$kid->id}}">
                                        </a>
                                    </div>
                                    @foreach ($kid->guardians as $guardian)
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="text-left px-md-3">
                                            <dd class="m-0 fs-6">{{$guardian->name}}</dd>
                                        </div>
                                        <nav class="nav-bar navbar-expand-lg navbar-light justify-content-center text-center">
                                            <!--　メニューボタン -->
                                            <button class="navbar-toggler py-0 border-0 fs-6 m-0" type="button" data-bs-toggle="collapse" data-bs-target="#navButtonsG{{$guardian->id}}"
                                                aria-controls="navButtonsG" aria-expanded="false" aria-label="Toggle navigation">
                                                <span class="navbar-toggler-icon"></span>
                                            </button>
                                            <div class="collapse navbar-collapse text-center px-md-3" id="navButtonsG{{$guardian->id}}">
                                                <!--　編集モーダル表示ボタン -->
                                                <a href="#guardian-edit" class="nav-item btn bi bi-pencil-square btn-outline-secondary border-0 bg-transparent mx-2 p-0"
                                                    type="button" data-bs-toggle="modal" data-id="{{$guardian->id}}">
                                                </a>
                                                <!--　削除モーダル表示ボタン -->
                                                <a href="#guardian-delete" class="nav-item btn bi bi-trash btn-outline-secondary border-0 bg-transparent mx-2 p-0"
                                                    type="button" data-bs-toggle="modal" data-id="{{$guardian->id}}">
                                                </a>
                                            </div>
                                        </nav>
                                    </div>
                                    @endforeach
                                </div>
                                <!-- 習い事 -->
                                <div class="col-6 m-0">
                                    <div class="text-center border-bottom d-flex justify-content-around align-items-center">
                                        <dt for="lesson" class="form-label p-0 m-0">習い事</dt>
                                        <!--　追加モーダル表示ボタン -->
                                        <a href="#lesson-create" class="btn bi bi-plus-square btn-outline-secondary border-0 bg-transparent p-0" data-bs-target="#lesson-create" type="button" data-bs-toggle="modal" data-id="{{$kid->id}}">
                                        </a>
                                    </div>
                                    @foreach ($kid->lessons as $lesson)
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="text-left px-md-3">
                                            <dd class="m-0 fs-6">{{$lesson->name}}</dd>
                                        </div>
                                        <nav class="nav-bar navbar-expand-lg navbar-light justify-content-center text-center">
                                            <!--　メニューボタン -->
                                            <button class="navbar-toggler py-0 border-0 fs-6 m-0" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#navButtonsL{{$lesson->id}}" aria-controls="navButtons" aria-expanded="false"
                                                aria-label="Toggle navigation">
                                                <span class="navbar-toggler-icon"></span>
                                            </button>
                                            <div class="collapse navbar-collapse text-center px-md-3" id="navButtonsL{{$lesson->id}}">
                                                <!--　編集モーダル表示ボタン -->
                                                <a href="#lesson-edit"
                                                    class="nav-item btn bi bi-pencil-square btn-outline-secondary border-0 bg-transparent mx-2 p-0"
                                                    type="button" data-bs-toggle="modal" data-id="{{$lesson->id}}">
                                                </a>
                                                <!--　削除モーダル表示ボタン -->
                                                <a href="#lesson-delete"
                                                    class="nav-item btn bi bi-trash btn-outline-secondary border-0 bg-transparent mx-2 p-0"
                                                    type="button" data-bs-toggle="modal" data-id="{{$lesson->id}}">
                                                </a>
                                            </div>
                                        </nav>
                                    </div>
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
    <!-- 保護者追加モーダル -->
    <div class="modal fade" id="guardian-create" tabindex="-1" role="dialog" aria-labelledby="#createGuardian"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGuardian">保護者を追加する</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row px-3">
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="grade_name" class="form-label me-2 my-0 p-0">クラス：</label>
                            <p class="text-secondary kid-grade my-0"></p>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="kid_name" class="form-label me-2 my-0">園児名：</label>
                            <p class="text-secondary kid-name my-0"></p>
                        </div>
                    </div>
                    <table class="table table-hover table-responsive table-bordered" id="guardian-table">
                        <thead class="thead-default">
                            <tr>
                                <th class="col-3 text-center">続柄</th>
                                <th class="col-9 text-center">名前</th>
                            </tr>
                        </thead>
                        <tbody class="guardians">
                            <tr>
                                <td scope="row" class="col-3"><input type="text" name="relation"
                                        class="col-12 bg-transparent text-center"></td>
                                <td class="col-9"><input type="text" name="name" class="col-12 bg-transparent text-center">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary btn-modal-submit" id="guardian-store">追加</button>
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
                    <table class="table table-hover table-responsive table-bordered" id="guardian-table">
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
                    <button type="button" class="btn btn-primary btn-modal-submit" id="guardian-update">更新</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 保護者削除モーダル -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" id="guardian-delete" role="dialog"
        aria-labelledby="guardian-show-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">保護者を削除しますか?</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-responsive table-bordered" id="guardian-table">
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
                    <button type="button" class="btn btn-primary btn-modal-submit" value="" id="guardian-destroy">削除</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 習い事追加モーダル -->
    <div class="modal fade" id="lesson-create" tabindex="-1" role="dialog" aria-labelledby="#createLesson" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLesson">習い事を追加する</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row px-3">
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="grade_name" class="form-label me-2 my-0 p-0">クラス：</label>
                            <p class="text-secondary kid-grade my-0"></p>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="kid_name" class="form-label me-2 my-0">園児名：</label>
                            <p class="text-secondary kid-name my-0"></p>
                        </div>
                    </div>
                    <div class="row px-3 align-items-center mb-3 lesson_name">
                        <label for="lesson_name" class="col-form-label col-md-4 p-0">習い事：</label>
                        <div class="col-md-8 px-0">
                            <input class="form-control p-2" type="text" name="lesson_name" value="">
                        </div>
                    </div>
                    <div class="row px-3 align-items-center mb-3 lesson_schedule">
                        <label for="lesson_name" class="col-form-label col-md-4 p-0">曜日：</label>
                        <div class="col-md-8 px-0">
                            <select class="form-control p-2" type="text" name="lesson_schedule">
                                <option value="1">月</option>
                                <option value="2">火</option>
                                <option value="3">水</option>
                                <option value="4">木</option>
                                <option value="5">金</option>
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center px-3 mb-3 pu_guardian">
                        <label for="pu_guardian_id" class="form-label col-md-4 p-0 m-0">お迎えに来る人：</label>
                        <select class="form-select-lg p-2 col-md-8" name="pu_guardian_id">
                        </select>
                    </div>
                    <div class="row align-items-center px-3 mb-3 pu_plan">
                        <label for="pu_plan_time" class="form-label col-md-4 p-0">お迎えの予定：</label>
                        <div class="d-flex justify-content-between align-items-center col-md-8 px-0">
                            <input class="form-control" id="pu_plan_hour" type="number" name="pu_plan_hour" value="">
                            <p class="text-secondary mx-3 my-0">:</p>
                            <input class="form-control" type="number" name="pu_plan_minute" id="pu_plan_minute" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary btn-modal-submit" id="lesson-store">追加</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 習い事編集モーダル -->
    <!-- 習い事削除モーダル -->
</div>
@endsection

