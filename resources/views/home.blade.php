@extends('layouts.app')

@section('content')

<div class="card mt-3">
    <div class="card-header">
        登降園記録をつける
    </div>
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="record_grade" class="mb-1">クラス</label>
                    <select id="record_grade" class="form-select mb-3" name="grade_id">
                        <option value="0">全園児一覧</option>
                        @foreach ($grades as $grade )
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <label for="record_kids" class="mb-1">お子さんの名前</label>
                <div class="list-group mb-3" id="record_kids" role="tablist">
                    @foreach ($kids as $kid )
                        <a type="button" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#attendance-record" role="tab" value="{{ $kid->id }}">{{ $kid->name }}</a>
                    @endforeach
                </div>
                {{ $kids->links() }}
            </div>
        </div>
    </div>
</div>
<!-- 登録モーダル -->
<div class="modal fade" data-bs-backdrop="static" tabindex="-1" id="attendance-record" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBasicLabel">登園記録をつける</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
                <form class="px-3 mb-0">
                    <!-- 日付、園児名、クラスは自動で入力 -->
                    <h4 for="date" class="row record-date h4"></h4>
                    <div class="row px-3">
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="grade_name" class="form-label me-2 my-0 p-0">クラス：</label>
                            <p class="text-secondary record-grade my-0"></p>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="kid_name" class="form-label me-2 my-0">園児名：</label>
                            <p class="text-secondary record-kid my-0"></p>
                        </div>
                    </div>
                    <!-- 登園 -->
                    <div class="morning">
                        <div class="row align-items-center px-3 mb-3 do_guardian">
                            <label for="do_guardian_id" class="form-label col-md-4 p-0">お送りに来た人：</label>
                            <select class="form-select-lg p-2 col-md-8" name="do_guardian_id"></select>
                        </div>
                        <div class="row align-items-center px-3 mb-3 pu_guardian">
                            <label for="pu_plan_guardian_id" class="form-label col-md-4 p-0">お迎えに来る人：</label>
                            <select class="form-select-lg p-2 col-md-8" name="pu_plan_guardian_id">
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
                    <!-- 降園 -->
                    <div class="leave">
                        <div class="row align-items-center px-3 mb-3 pu_guardian">
                            <label for="pu_guardian_id" class="form-label col-md-4 p-0">お迎えに来た人：</label>
                            <select class="form-select-lg p-2 col-md-8" name="pu_guardian_id">
                            </select>
                        </div>
                    </div>
                    <div class="form-row justify-content-center morning d-flex">
                        <button class="btn btn-info btn-lg btn-modal-submit morning" value="" id="attend">登園</button>
                    </div>
                    <div class="form-row justify-content-center d-flex leave">
                        <button class="btn btn-info btn-lg btn-modal-submit leave" value="" id="leave">降園</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
