tang.block([
    '$_/data/', '$_/async/',
    '$_/dom/Elements'
], function(_) {
    var $ = _.dom.$,
        api = $('input[name=__api__]').val();
    $('.signin input').click(function() {
        var un = $('input[name=username]').val(),
            pw = $('input[name=password]').val(),
            pc = $('input[name=pincode]').val();

        if (un.length >= 5) {
            if (pw.length >= 4) {
                if (pc.length === 6) {
                    _.async.ajax(api + '?app=logger&c=VISA', {
                        method: 'POST',
                        data: 'username=' + un + '&password=' + pw + '&pincode=' + pc,
                        success: function(result) {
                            result = _.data.decodeJSON(result);
                            if (result.msg === 'Visaed!' && result.data.username === un) {
                                location.reload();
                            } else {
                                switch (result.msg) {
                                    case 'Pin Code Not Match!':
                                        alert('个人认证码错误');
                                        $('input[name=pincode]').val('');
                                        break;

                                    case 'Password Not Match!':
                                        alert('密码错误');
                                        $('input[name=password]').val('');
                                        $('input[name=pincode]').val('');
                                        break;

                                    case 'Unregister User!':
                                        alert('用户不存在');
                                        $('input[name=username]').val('');
                                        $('input[name=password]').val('');
                                        $('input[name=pincode]').val('');
                                        break;
                                }
                            }
                        },
                        fail: function(result) {
                            alert('网络或服务器错误');
                        }
                    });
                } else {
                    alert('passcode length must 6.');
                }
            } else {
                alert('password length must greater than 4.');
            }
        } else {
            alert('username length must greater than 5.');
        }
    });
}, true);