const { Callbacks } = require("jquery");


window.onload = function () {
    $(function () {

        //MMDD形式に変更
        function dateToMMDD(date) {
            MM = date.getMonth() + 1;
            DD = date.getDate();
            return MM + '/' + DD;
        };

        //Hi形式に変更
        function dateToHi(date) {
            HH = ("0" + date.getHours()).slice(-2);
            ii = ("0" + date.getMinutes()).slice(-2);
            return HH + ':' + ii;
        };

        //YYYY-MM-DD HH:ii:ss形式に変更
        function dateToFullStr(date) {
            YYYY = date.getFullYear();
            MM = ("0" + (date.getMonth() + 1)).slice(-2);
            DD = ("0" + date.getDate()).slice(-2);
            HH = ("0" + date.getHours()).slice(-2);
            ii = ("0" + date.getMinutes()).slice(-2);
            ss = ("0" + date.getSeconds()).slice(-2);
            return YYYY + '-' + MM + '-' + DD + ' ' + HH + ':' + ii + ':' + ss;
        };

        //文字列からdate形式に
        function strToDate(timeStr, dateStr) {
            timeArr = timeStr.split(':');
            dateArr = dateStr.split('/');

            YYYY = new Date().getFullYear();
            MM = ("0" + dateArr[0]).slice(-2);
            DD = ("0" + dateArr[1]).slice(-2);
            HH = ("0" + timeArr[0]).slice(-2);
            ii = ("0" + timeArr[1]).slice(-2);
            return YYYY + '-' + MM + '-' + DD + ' ' + HH + ':' + ii + ':00';
        };

        //ajax通信
        function doAjax(url, data={}, method = "GET") {
            let d = new $.Deferred;
            $.ajax({
                type: method,
                url: url,
                data: data,
                dataType: "json",
            }).done(function (data) {
                d.resolve(data);
            }).fail(function (res) {
                let message = res.responseJSON.errors;
                d.reject(message);
            });

            return d.promise();
        };

        //保護者をオプションにセット
        function setGuardians(guardians, lesson = null, record = null) {

            let do_guardian = '.do_guardian select';
            let pu_guardian = '.pu_guardian select';
            let chkDo = null, chkPu = null;

            const setOptions = function (chkDo, chkPu) {
                //do,puのoptionを設定
                $.each(guardians, function (index, guardian) {
                    const appendOption = function (cls, chkId = null) {
                        //selectedを設定
                        if (guardian.id == chkId) {
                            $(cls).append($('<option>').text(guardian.name).attr({ 'value': guardian.id, 'selected': 'selected' }));
                        } else {
                            $(cls).append($('<option>').text(guardian.name).attr({ 'value': guardian.id }));
                        }
                    };
                    appendOption(do_guardian, chkDo);
                    appendOption(pu_guardian, chkPu);
                })
            };

            //一旦option削除
            $(do_guardian+','+ pu_guardian).empty();

            //
            if (lesson) {
                chkPu = lesson.pu_plan_guardian_id
                setOptions(chkDo, chkPu);
            } else if (record) {
                chkDo = record.do_guardian_id;
                chkPu = record.pu_guardian_id;
                setOptions(chkDo, chkPu);
            } else {
                setOptions();
            };

        };

        //閉じるボタンに
        function changeToClose(button) {

            //ボタンを閉じるボタンに
            button.text('閉じる');

            //閉じるボタンを押したら
            $(".btn-modal-submit").on('click', function (e) {
                var modal = $(this).parents('.modal');
                //閉じる
                modal.modal('hide');
                //画面を更新
                location.reload();
            });
        };

        //成功メッセージを表示
        function successMessage(message) {
            $('.modal-body').prepend('<p class="alert alert-success" role="alert">' + message + '</p>');
        };

        function errorMessage(res,button) {
            let alertList = '';
            //登降園時間にエラーが出ている場合は
            if (res.do_time || res.pu_time) {
                alertList = '<li>すでに記録済みです</>';
                changeToClose(button);
            } else {
                //エラーメッセージを<li>に展開
                $.each(res, function (index, value) {
                    alertList += '<li>' + value + '</li>';
                });
            };
            //エラーメッセージを表示
            $('.modal-body').prepend('<ul class="alert alert-danger" role="alert">' + alertList + '</ul>');
        }

        //csrfトークンの設定
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //クラスを選ぶと園児名絞り込み(Home画面)
        $('#record_grade').on('change', function () {
            let grade_id = $(this).val();
            let url = "grades";
            let data = { 'grade': grade_id };

            doAjax(url, data).then(function (data) {
                //optionをいったん削除
                $('#record_kids a').remove();
                //DBから受け取ったresponseからdataだけ取り出す
                let kids = data.data;
                //optionにセット
                $.each(kids, function (key, kid) {
                    $('#record_kids').append($('<a>').text(kid.name).attr({ 'class': "list-group-item list-group-item-action", 'data-bs-toggle': "modal", 'data-bs-target': "#attendance-record", 'role': "tab", 'value': kid.id, }));
                });
            });
        });

        //登降園モーダル表示
        $('#attendance-record').on('show.bs.modal', function (e) {
            let kid_id = $(e.relatedTarget).attr('value');
            let url = 'records/create';
            let data = { 'kid': kid_id };

            if ($('.alert').length) {
                $('.alert').remove();
            };

            doAjax(url, data).then(function (data) {

                const kid = data.kid;
                const guardians = kid.guardians;
                let record = data.record;

                //曜日が一致する習い事のみ取り出す
                const dayOfToday = new Date().getDay();
                const lesson = $.grep(kid.lessons, lesson => lesson.schedule == dayOfToday)[0];

                //日付
                $('.record-date').text(dateToMMDD(new Date));

                //クラス・園児名
                $('.record-grade').text(kid.grade.name);
                $('.record-kid').text(kid.name);

                //登園か降園か切り替える
                if (record.length) {
                    $('.morning').hide();
                    $('.leave').show();
                } else {
                    $('.morning').show();
                    $('.leave').hide();
                };


                //保護者をoptionにセット
                setGuardians(guardians, lesson)

                //迎えの予定時間をデフォルト表示
                if (lesson) {
                    $('#pu_plan_hour').val(lesson.pu_hour);
                    $('#pu_plan_minute').val(lesson.pu_minute);

                } else {
                    $('#pu_plan_hour').val(15);
                    $('#pu_plan_minute').val(30);
                };

                //ボタンにkid_idを渡す
                $('.btn-modal-submit').val(kid_id);

            }, function (message) {
                alert(message);
            });
        });

        //登園記録
        $('#attend').on('click', function (e) {
            e.preventDefault();

            if ($('.alert').length) {
                $('.alert').remove();
            };

            let url = '/records';
            let kid_id = $(this).val();
            let data = {
                'kid_id': kid_id,
                'do_guardian_id': $('.do_guardian select').val(),
                'do_time': dateToFullStr(new Date),
                'pu_plan_guardian_id': $('.pu_guardian select').val(),
                'pu_plan_hour': $('#pu_plan_hour').val(),
                'pu_plan_minute': $('#pu_plan_minute').val(),
            };
            let message = $('.record-kid').text() + 'さん おはようございます。';
            let button = $(this);

            doAjax(url, data, 'POST').then(function (data) {
                successMessage(message);
                changeToClose(button);
            }, function (res) {
                errorMessage(res, button);
            });
        });

        //降園記録
        $('#leave').on('click', function (e) {
            e.preventDefault();

            if ($('.alert').length) {
                $('.alert').remove();
            };

            let kid_id = $(this).val();
            let url = '/records/' + kid_id + '/leave';
            let data = {
                'kid_id': kid_id,
                'pu_guardian_id': $('.pu_guardian select').val(),
                'pu_time': dateToFullStr(new Date)
            };
            let message = $('.record-kid').text() + 'さん　さようなら';
            let button = $(this);

            doAjax(url, data, 'POST').then(function (data) {
                successMessage(message);
                changeToClose(button);
            }, function (res) {
                errorMessage(res, button);
            });

        })

        //レコード編集モーダル表示
        $('#record-edit').on('show.bs.modal', function (e) {
            let record_id = $(e.relatedTarget).attr('value');
            let url = 'records/' + record_id + '/edit';
            let data = { 'record': record_id };

            if ($('.alert').length) {
                $('.alert').remove();
            };

            doAjax(url, data).then(function (data) {
                const kid = data.kid;
                const guardians = kid.guardians;
                const record = data.record;

                //クラス・園児名
                $('.record-grade').text(kid.grade.name);
                $('.record-kid').text(kid.name);

                //日付
                const record_date = new Date(record.created_at);
                $('.record-date').text(dateToMMDD(record_date));

                setGuardians(guardians, null, record);

                //見やすいように時刻をフォーマット
                $('.do_time input').val(dateToHi(new Date(record.do_time)));
                //迎えに来た時間が記録されている場合のみ時間を表示
                if (record.pu_time) {
                    $('.pu_time input').val(dateToHi(new Date(record.pu_time)));
                } else {
                    $('.pu_time input').val('');
                };

                //ボタンにrecord_idを渡す
                $('.btn-modal-submit').val(record_id);
            });
        });

        //レコード編集
        $('#record-update').on('click', function (e) {
            e.preventDefault();

            if ($('.alert').length) {
                $('.alert').remove();
            };

            let record_id = $(this).val();
            let url = '/records/' + record_id;
            let dateStr = $('.record-date').html();
            let data = {
                '_method': "PUT",
                'do_guardian_id': $('.do_guardian select').val(),
                'do_time': strToDate($('.do_time input').val(), dateStr),
                'pu_guardian_id': $('.pu_guardian select').val(),
                'pu_time': strToDate($('.pu_time input').val(), dateStr)
            };
            let message = '更新できました。';
            let button = $(this);

            doAjax(url, data, 'POST').then(function (data) {
                successMessage(message);
                changeToClose(button);
            }, function (res) {
                errorMessage(res, button);
            });

        });

        //レコード削除モーダル表示
        $('#record-delete').on('show.bs.modal', function (e) {
            let record_id = $(e.relatedTarget).attr('value');
            let url = 'records/' + record_id + '/edit';
            let data = { 'record': record_id };

            if ($('.alert').length) {
                $('.alert').remove();
            };

            doAjax(url, data).then(function (data) {
                const kid = data.kid;
                const guardians = kid.guardians;
                const record = data.record;

                //クラス・園児名
                $('.record-grade').text(kid.grade.name);
                $('.record-kid').text(kid.name);

                //日付
                const record_date = new Date(record.created_at);
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

            });
            //ボタンにrecord_idを渡す
            $('.btn-modal-submit').val(record_id);
        });

        //レコード削除
        $('#record-destroy').on('click', function (e) {
            e.preventDefault();

            if ($('.alert').length) {
                $('.alert').remove();
            };

            let record_id = $(this).val();
            let url = '/records/' + record_id;
            let data = { '_method': "DELETE" };
            let message = '削除できました。';
            let button = $(this);

            doAjax(url, data, 'POST').then(function (data) {
                successMessage(message);
                changeToClose(button);
            }, function (res) {
                errorMessage(res, button);
            });
        });

        //保護者追加モーダル表示

        //保護者追加

        //保護者編集モーダル表示
        $('#guardian-edit').on('show.bs.modal', function (e) {
            let guardian_id = $(e.relatedTarget).data('id');
            let url = '/guardians/' + guardian_id + '/edit';
            let guardianRows;

            if ($('.alert').length) {
                $('.alert').remove();
            };

            $('.guardians').empty();

            doAjax(url).then(function (data) {
                let guardian = data.guardians;

                guardianRows = '<tr><input type="hidden" value="' + guardian.id + '" name="guardian_id"><td scope="row" class="col-3"><input type="text" value="' + guardian.relation + '" class="col-12 border-0 bg-transparent text-center" name="relation"></td><td class="col-9"><input type="text" value="' + guardian.name + '" class="col-12 border-0 bg-transparent text-center" name="name"></td></tr>';

                $('.guardians').append(guardianRows);
            });
        });

        //保護者更新
        $('#guardian-update').on('click', function (e) {
            e.preventDefault();

            if ($('.alert').length) {
                $('.alert').remove();
            };

            let guardian = $('#guardian-table>.guardians tr');
            let guardian_id = $(guardian).find('input[name="guardian_id"]').val();
            let relation = $(guardian).find('input[name="relation"]').val();
            let name = $(guardian).find('input[name="name"]').val();
            let button = $(this);
            let url = '/guardians/'+guardian_id;
            let data = { '_method': 'PUT', 'relation': relation, 'name': name };

            doAjax(url, data, 'POST').then(function (data) {
                successMessage('更新できました。');
                changeToClose(button);
            }, function (res) {
                errorMessage(res, button);
            })

        })

        //保護者削除モーダル表示
        $('#guardian-delete').on('show.bs.modal', function (e) {
            let guardian_id = $(e.relatedTarget).data('id');
            let url = '/guardians/' + guardian_id + '/edit';
            let guardianRows;

            $('.guardians').empty();

            doAjax(url).then(function (data) {
                let guardian = data.guardians;
                guardianRows = '<tr><td scope="row" class="col-3 text-center">' + guardian.relation + '</td><td class="col-9 text-center">' + guardian.name + '</td><tr>';

                $('.guardians').append(guardianRows);
                //ボタンにguardian_idを渡す
                $('.btn-modal-submit').val(guardian_id);
            });

        })

        //保護者削除
        $('#guardian-destroy').on('click', function (e) {
            e.preventDefault();

            if ($('.alert').length) {
                $('.alert').remove();
            };

            let guardian_id = $(this).val();
            let url = '/guardians/' + guardian_id;
            let data = { '_method': 'DELETE' };
            let message = '削除できました。';
            let button = $(this);

            doAjax(url, data, 'POST').then(function (data) {
                successMessage(message);
                changeToClose(button);
            }, function (res) {
                errorMessage(res, button);
            });
        })

        //習い事編集モーダル表示

        //習い事更新

        //習い事削除モーダル表示

        //習い事削除

    });
};

