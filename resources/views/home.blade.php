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
                    <label for="record_grade">クラス</label>
                    <select id="record_grade" class="form-select form-control" name="grade_id">
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
                <label for="record_kids">お子さんの名前</label>
                <div class="list-group" id="record_kids" role="tablist">
                    @foreach ($kids as $kid )
                        <a type="button" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#create-record" role="tab" value="{{ $kid->id }}">{{ $kid->name }}</a>
                    @endforeach
                </div>
                {{ $kids->links() }}
            </div>
        </div>
    </div>
</div>
<!-- 登録モーダル -->
<div class="modal fade"data-backdrop="static" tabindex="-1" id="create-record" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBasicLabel">登園記録をつける</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                <form class="mb-0">
                    <div class="form-row">
                        <div class="form-group align-items-center col-md-6">
                            <h6 for="date" class="record-date"></h6>
                        </div>
                    </div>
                    <!-- 園児名とクラスは自動で入力 -->
                    <div class="form-row">
                        <div class="form-group align-items-center col-md-6">
                            <label for="grade_name" class="col-formLabel text-md-left">クラス：</label>
                            <p class="text-secondary record-grade"></p>
                        </div>
                        <div class="form-group align-items-center col-md-6">
                            <label for="kid_name" class="col-formLabel text-md-left">園児名：</label>
                            <p class="text-secondary record-kid"></p>
                        </div>
                    </div>
                    <div class="form-row morning">
                        <div class="form-group align-items-center col-md-12 do_guardian">
                            <label for="do_guardian_id" class="col-formLabel mr-3">お送りに来た人</label>
                            <select class="form-select-lg p-2 col-md-8" name="do_guardian_id">
                            </select>
                        </div>
                    </div>
                    <div class="form-row morning">
                        <div class="form-group align-items-center col-md-12 pu_guardian">
                            <label for="pu_plan_guardian_id" class="col-formLabel mr-3">お迎えに来る人</label>
                            <select class="form-select-lg p-2 col-md-8" name="pu_plan_guardian_id">
                            </select>
                        </div>
                    </div>
                    <div class="form-row morning">
                        <div class="form-group align-items-center col-md-12 pu_plan">
                            <label for="pu_plan_time" class="mr-3">お迎えの予定</label>
                            <div class="d-flex justify-content-center">
                                <div class="col-6 col-md-6">
                                    <input class="form-control" id="pu_plan_hour" type="number" name="pu_plan_hour" value="">
                                </div>

                                <p class="text-secondary align-items-center my-2">:</p>
                                <div class="col-6 col-md-6">
                                    <input class="form-control" type="number" name="pu_plan_minute" id="pu_plan_minute" value="">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-row leave">
                        <div class="form-group align-items-center col-md-12 pu_guardian">
                            <label for="pu_guardian_id" class="col-formLabel mr-3">お迎えに来た人</label>
                            <select class="form-select-lg p-2 col-md-8" name="pu_guardian_id">
                            </select>
                        </div>
                    </div>
                    <div class="form-row justify-content-center d-flex">
                        <button class="btn btn-info btn-lg btn-modal-submit morning" data-route="record-store" value="">登園</button>
                    </div>
                    <div class="form-row justify-content-center d-flex">
                        <button class="btn btn-info btn-lg btn-modal-submit leave" data-route="record-leave" value="">降園</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
