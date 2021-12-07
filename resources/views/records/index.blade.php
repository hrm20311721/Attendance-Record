@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 table-responsive">
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th scope="col">日付</th>
                        <th scope="col">園児名</th>
                        <th scope="col">クラス</th>
                        <th scope="col">登園時間</th>
                        <th scope="col">送りに来た人</th>
                        <th scope="col">降園予定時間</th>
                        <th scope="col">迎えに来る人</th>
                        <th scope="col">降園時間</th>
                        <th scope="col">迎えに来た人</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $record )
                    <tr>
                        <td scope="row">{{ $record->created_at->format('m/d')}}</td>
                        <td>{{ $record->kid->name }}</td>
                        <td>{{ $record->kid->grade->name }}</td>
                        <td>{{ $record->do_time->format('H:i') }}</td>
                        <td>{{ $record->do_guardian->name }}</td>
                        <td>{{ $record->pu_plan_hour }}:{{ $record->pu_plan_minute }}</td>
                        <td>{{ $record->pu_plan_guardian->name }}</td>
                        <!--迎えに来た時間が記録されていれば -->
                        @if ($record->pu_time)
                            <!-- 記録を表示 -->
                            <td>{{ $record->pu_time->format('H:i') }}</td>
                            <td>{{ $record->pu_guardian->name }}</td>
                        @else
                            <!-- なければ空欄 -->
                            <td></td>
                            <td></td>
                        @endif

                        <!-- 管理者権限以上で編集ボタン表示　-->
                        @can('admin-higher')
                            <td>
                                <div class="btn-group align-items-center">
                                    <!--　編集モーダルを開くボタン -->
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#record-edit" value="{{$record->id}}">
                                        <i class="far fa-edit edit-item"></i>
                                    </button>
                                    <!-- 削除確認モーダルを開くボタン -->
                                    <button type="submit" class="btn btn-outline-secondary"data-bs-toggle="modal" data-bs-target="#record-delete" value="{{ $record->id }}">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $records->links() }}
        </div>
    </div>
    <!-- 編集モーダル -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" id="record-edit" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBasicLabel">レコードを編集する</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <!-- 園児名とクラスは更新できない -->
                    <h6 for="date" class="record-date"></h6>
                    <div class="row px-3">
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="grade_name" class="form-label me-2 my-0 p-0">クラス：</label>
                            <p class="text-secondary record-grade my-0" id="grade"></p>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-3 p-0">
                            <label for="kid_name" class="form-label me-2 my-0">園児名：</label>
                            <p class="text-secondary record-kid my-0"></p>
                        </div>
                    </div>
                    <!--　送りに来た人 -->
                    <div class="row align-items-center px-3 mb-3 do_guardian">
                        <label for="do_guardian_id" class="form-label col-md-4 p-0 m-0">お送りに来た人：</label>
                        <select class="form-select-lg p-2 col-md-8" name="do_guardian_id">
                        </select>
                    </div>
                    <!-- 送りに来た時間 -->
                    <div class="row px-3 align-items-center mb-3 do_time">
                        <label for="do_time" class="col-form-label col-md-4 p-0">お送りに来た時間：</label>
                        <div class="col-md-8 px-0">
                            <input class="form-control p-2" type="text" name="do_time" value="">
                        </div>
                    </div>


                    <!-- 迎えに来た人 -->
                    <div class="row align-items-center px-3 mb-3 pu_guardian">
                        <label for="pu_guardian_id" class="form-label col-md-4 p-0 m-0">お送りに来た人：</label>
                        <select class="form-select-lg p-2 col-md-8" name="pu_guardian_id">
                        </select>
                    </div>
                    <!-- 迎えに来た時間 -->
                    <div class="row px-3 align-items-center mb-3 pu_time">
                        <label for="pu_time" class="col-form-label col-md-4 p-0">お送りに来た時間：</label>
                        <div class="col-md-8 px-0">
                            <input class="form-control p-2" type="text" name="pu_time" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button class="btn btn-primary btn-modal-submit" id="record-update" value="">保存</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 削除確認モーダル -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" id="record-delete" role="dialog"
        aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="date" class="record-date"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 kid_name">
                            <label for="kid_name" class="text-md-left">クラス：</label>
                            <p class="text-secondary record-grade"></p>
                        </div>
                        <div class="col-md-6">
                            <label for="kid_name" class="text-md-left">園児名：</label>
                            <p class="text-secondary record-kid"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 do_guardian">
                            <label for="do_guardian_name" class="text-md-left">お送りに来た人：</label>
                            <p class="text-secondary" data-value="do_guardian"></p>
                        </div>
                        <div class="col-md-6 do_time">
                            <label for="do_time" class="text-md-left">お送りに来た時間：</label>
                            <p class="text-secondary"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pu_guardian">
                            <label for="pu_guardian_name" class="text-md-left">お迎えに来た人：</label>
                            <p class="text-secondary" data-value="pu_guardian"></p>
                        </div>
                        <div class="col-md-6 pu_time">
                            <label for="pu_time" class="text-md-left">お迎えに来た時間：</label>
                            <p class="text-secondary"></p>
                        </div>
                    </div>
                    <h5 class="modal-title" id="modalBasicLabel">レコードを削除しますか？</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-primary btn-modal-submit" id="record-destroy" value="">削除</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
