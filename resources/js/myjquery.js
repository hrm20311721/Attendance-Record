window.onload = function () {
    $(function () {

        var dateToMMDD = function (date) {
            MM = date.getMonth() + 1;
            DD = date.getDate();
            return MM + '/' + DD;
        };
        var dateToHi = function (date) {
            HH = ("0" + date.getHours()).slice(-2);
            ii = ("0" + date.getMinutes()).slice(-2);
            return HH + ':' + ii;
        }
        var dateToFullStr = function (date) {
            YYYY = date.getFullYear();
            MM = ("0" + (date.getMonth() + 1)).slice(-2);
            DD = ("0" + date.getDate()).slice(-2);
            HH = ("0" + date.getHours()).slice(-2);
            ii = ("0" + date.getMinutes()).slice(-2);
            ss = ("0" + date.getSeconds()).slice(-2);
            return YYYY + '-' + MM + '-' + DD + ' ' + HH + ':' + ii + ':' + ss;
        }

        var strToDate = function (timeStr,dateStr) {
            timeArr = timeStr.split(':');
            dateArr = dateStr.split('/');

            YYYY = new Date().getFullYear();
            MM = ("0" + dateArr[0]).slice(-2);
            DD = ("0" + dateArr[1]).slice(-2);
            HH = ("0" + timeArr[0]).slice(-2);
            ii = ("0" + timeArr[1]).slice(-2);
            return YYYY + '-' + MM + '-' + DD + ' ' + HH + ':' + ii + ':00';
        }

        //csrfトークンの設定
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //クラスを選ぶと園児名絞り込み
        $('#record_grade').on('change', function () {
            var grade_id = $(this).val();

            $.ajax({
                type: "GET",
                url: "grades",
                data: { 'grade': grade_id },
                dataType: "json"
            }).done(function (data) {
                //optionをいったん削除
                $('#record_kids a').remove();
                //DBから受け取ったresponseからdataだけ取り出す
                data = data.data;
                //optionにセット
                $.each(data, function (key, value) {
                    $('#record_kids').append($('<a>').text(value.name).attr({ 'class': "list-group-item list-group-item-action", 'data-bs-toggle': "modal", 'data-bs-target': "#create-record", 'role': "tab", 'value': value.id, }));
                });
            }).fail(function (res) {
                var res = res.responseJSON.errors;
                alert(res);
            });

        });

        //モーダルを表示
        $('.modal').on('show.bs.modal', function (e) {
            var id = $(e.relatedTarget).attr('value'); //record_idかkid_idを受け取る
            var route;
            var data;
            if ($('.alert').length) {
                $('.alert').remove();
            };
            //editかdeleteならeditルート。createならcreateルート。
            if ($(this).attr('id') == 'edit-record' || $(this).attr('id') == 'delete-record') {
                route = 'records/' + id + '/edit';
                data = { 'record': id };
            } else if ($(this).attr('id') == 'create-record') {
                route = 'records/create';
                data = { 'kid': id};
            };
            $('.btn-modal-submit').val(id);

            //idを渡して紐づく情報を受け取る
            $.ajax({
                type: "GET",
                url: route,
                data: data,
                dataType: "json",
                context:this
            }).done(function (data) {

                var record = data.record;
                var kid = data.kid;
                var guardians = kid.guardians;

                $('.record-grade').text(kid.grade.name);
                $('.record-kid').text(kid.name);


                //編集ボタンが押された場合
                if ($(this).attr('id') == 'edit-record') {

                    var record_date = new Date(record.created_at);

                    $('.record-date').text(dateToMMDD(record_date));

                    //一旦optionを削除
                    $('.do_guardian option').remove();
                    $('.pu_guardian option').remove();
                    //optionをセット
                    $.each(guardians, function (key, value) {
                        //レコードに記録されているoptionをselectedに
                        if (value.id == record.do_guardian_id) {
                            $('.do_guardian select').append($('<option>').text(value.name).attr({ 'value': value.id, 'selected':''}));
                        } else {
                            $('.do_guardian select').append($('<option>').text(value.name).attr('value', value.id));
                        };
                        //レコードに記録されているoptionをselectedに
                        if (value.id == record.pu_guardian_id) {
                            $('.pu_guardian select').append($('<option>').text(value.name).attr({ 'value': value.id, 'selected': 'selected' }));
                        } else {
                            $('.pu_guardian select').append($('<option>').text(value.name).attr('value', value.id));
                        };
                    });

                    //見やすいように時刻をフォーマット
                    $('.do_time input').val(dateToHi(new Date(record.do_time)));
                    //迎えに来た時間が記録されている場合のみ時間を表示
                    if (record.pu_time) {
                        $('.pu_time input').val(dateToHi(new Date(record.pu_time)));
                    } else {
                        $('.pu_time input').val('');
                    };

                    //サブミットボタンにrecord_idを渡す

                    $('.btn-modal-submit').val()

                //削除ボタンが押された場合
                } else if ($(this).attr('id') == 'delete-record') {

                    var record_date = new Date(record.created_at);

                    $('.record-date').text(dateToMMDD(record_date));


                    //レコードから保護者の名前を取得
                    var do_guardian_name = $.grep(guardians, function (key, value) {
                        return (key.id == record.do_guardian_id);
                    })[0].name;
                    var pu_guardian_name = '';
                    //迎えに来た人が記録されている場合は名前を取得
                    if (record.pu_guardian_id) {
                        pu_guardian_name = $.grep(guardians, function (key, value) {
                            return (key.id == record.pu_guardian_id);
                        })[0].name;
                    };

                    $('.do_guardian p').text(do_guardian_name);
                    $('.pu_guardian p').text(pu_guardian_name);

                    //見やすいように日付をフォーマット
                    $('.do_time p').text(dateToHi(new Date(record.do_time)));
                    //迎えに来た時間が記録されている場合のみ時間を表示
                    if (record.pu_time) {
                        $('.pu_time p').text(dateToHi(new Date(record.pu_time)));
                    } else {
                        $('.pu_time p').text('');
                    };

                //登録用ボタンが押された場合
                } else if ($(this).attr('id') == 'create-record') {
                    var dayOfToday = new Date().getDay();
                    var lessons = kid.lessons;
                    //曜日が一致する習い事のみ取り出す
                    var lesson = $.grep(lessons, function (key, value) {
                        return (key.schedule == dayOfToday);
                    })[0];
                    $('.record-date').text(dateToMMDD(new Date));

                    //登園か降園か切り替える
                    if (data.record.length) {
                        $('.morning').hide();
                        $('.leave').show();
                    } else {
                        $('.morning').show();
                        $('.leave').hide();
                    }

                    //一旦optionを削除
                    $('.do_guardian option').remove();
                    $('.pu_guardian option').remove();

                    //習い事がある曜日は習い事に登録されている内容を規定値で表示させる
                    if (lesson) {
                        $('#pu_plan_hour').val(lesson.pu_hour);
                        $('#pu_plan_minute').val(lesson.pu_minute);
                        //
                        $.each(guardians, function (key, value) {
                            if (value.id == lesson.pu_plan_guardian_id) {
                                $('.pu_guardian select').append($('<option>').text(value.name).attr({ 'value': value.id, 'selected': 'selected' }));
                            } else {
                                $('.pu_guardian select').append($('<option>').text(value.name).attr('value', value.id));
                            }
                            $('.do_guardian select').append($('<option>').text(value.name).attr('value', value.id));
                        });
                    } else {
                        $('#pu_plan_hour').val(15);
                        $('#pu_plan_minute').val(30);
                        //optionをセット
                        $.each(guardians, function (key, value) {
                            $('.do_guardian select').append($('<option>').text(value.name).attr('value', value.id));
                            $('.pu_guardian select').append($('<option>').text(value.name).attr('value', value.id));
                        });
                    }

                };

            }).fail(function (res) {
                console.log(res);
            });
        });

        //モーダルでサブミット
        $('.btn-modal-submit').on('click', function (e) {
            e.preventDefault();　//デフォルトのアクションを止める

            if ($('.alert').length) {
                $('.alert').remove();
            };
            var route = $(this).data('route');
            var url;
            var id = $(this).val();
            var data = {};

            //登園記録
            if (route == 'record-store') {
                url = '/records';
                data = {
                    'kid_id': id,
                    'do_guardian_id': $('.do_guardian select').val(),
                    'do_time': dateToFullStr(new Date),
                    'pu_plan_guardian_id': $('.pu_guardian select').val(),
                    'pu_plan_hour': $('#pu_plan_hour').val(),
                    'pu_plan_minute': $('#pu_plan_minute').val(),
                };
                message = $('.record-kid').text() + 'さん おはようございます。';

            //降園記録
            } else if (route == 'record-leave') {
                url = '/records/' + id + '/leave';
                data = {
                    'kid_id': id,
                    'pu_guardian_id': $('.pu_guardian select').val(),
                    'pu_time': dateToFullStr(new Date)
                };
                message = $('.record-kid').text() + 'さん　さようなら';

            //記録編集
            } else if (route == 'record-update') {
                var dateStr = $('.record-date').html();
                url = '/records/' + id;
                data = {
                    '_method': "PUT",
                    'do_guardian_id': $('.do_guardian select').val(),
                    'do_time': strToDate($('.do_time input').val(), dateStr),
                    'pu_guardian_id': $('.pu_guardian select').val(),
                    'pu_time': strToDate($('.pu_time input').val(), dateStr)
                };
                message = '更新できました。';

            //記録削除
            } else if (route == 'record-destroy') {
                url = '/records/' + id;
                data = {'_method':"DELETE"};
                message = '削除できました。';
            };

            //サーバーに送る
            $.ajax({
                url: url,
                method: "POST",
                data: data,
                dataType: "json",
                context:this
            }).done(function (data) {
                //成功メッセージを表示
                $('.modal-body').prepend('<p class="alert alert-success" role="alert">' + message + '</p>');
                //ボタンを閉じるボタンに
                $(this).text('閉じる');

                //閉じるボタンを押したら
                $('.btn-modal-submit').on('click', function (e) {
                    var modal = $(this).parents('.modal');
                    //閉じる
                    modal.modal('hide');
                    //画面を更新
                    location.reload();
                });

            }).fail(function (res) {
                //レスポンスからエラー内容を格納
                var res = res.responseJSON.errors;
                //エラーメッセージを宣言
                var alertList = '';
                //登降園時間にエラーが出ている場合は
                if (res.do_time || res.pu_time) {
                    alertList = '<li>すでに記録済みです</>';
                    //ボタンを閉じるボタンに
                    $(this).text('閉じる');
                    $(this).on('click', function (e) {
                        $(this).parents('.modal').modal('hide');
                        location.reload();
                    })

                } else {
                    //エラーメッセージを<li>に展開
                    $.each(res, function (index, value) {
                        alertList += '<li>' + value + '</li>';
                    });
                }

                //エラーメッセージを表示
                $('.modal-body').prepend('<ul class="alert alert-danger" role="alert">' + alertList + '</ul>');

            });

        });

    });
};
