//  random hex string generator
var randHex = function(len) {
    var maxlen = 8,
        min = Math.pow(16,Math.min(len,maxlen)-1)
    max = Math.pow(16,Math.min(len,maxlen)) - 1,
        n  = Math.floor( Math.random() * (max-min+1) ) + min,
        r  = n.toString(16);
    while (r.length < len) {
        r = r + randHex( len - maxlen );
    }
    return r;
};


let text = $('.field-lab1form-text');
let key = $('.field-lab1form-key');
key.hide();
text.hide();
$(".result-block, #generate-block").hide();

let algorithm = $('#lab1form-algorithm');
let sendBtn = $('#crypt');

if(algorithm.val()) {
    key.show();text.show();
}

sendBtn.prop('disabled', true);

$('#lab1form-algorithm, #lab1form-text, #lab1form-key').change(function () {
    if(algorithm.val() == '' || $('#lab1form-text').val() == '') {
        sendBtn.prop('disabled', true);
    } else {
        sendBtn.prop('disabled', false);
    }
});

algorithm.change(function () {
    key.show();
    text.show();
    $("#generate-block").show();
    $('#lab1form-text').val('');
    $('#lab1form-key').val('');
    $('.result-block').hide();
    if(algorithm.val() == 1) {
        $('#16bytes, #24bytes, #32bytes').prop('disabled', true);
        $('#8bytes').prop('disabled', false);
    } else if(algorithm.val() == 2) {
        $('#8bytes').prop('disabled', true);
        $('#16bytes, #24bytes, #32bytes').prop('disabled', false);
    }
});

sendBtn.click(function () {
    $.ajax({
        url: 'http://localhost:8080/labs/perform',
        type: 'post',
        data: {
            algorithm: algorithm.val(),
            text: $('#lab1form-text').val(),
            key: $('#lab1form-key').val(),
            mode: $('#lab1form-mode').val(),
        },
        dataType: 'json',
        success: function(res) {
            $('.result-error').hide();
            $('#key').text(res.key);
            $('#key-length').text(res.length);
            $('#hash').text(res.hash);
            $('#de-hash ').text(res.deHash);
            $(".result-block").show();
            if(res.error) {
                $('.result-error').show();
                $('#error').text(res.error);
                $('.result-success').hide();
            } else {
                $('.result-error').hide();
                $('.result-success').show();
            }
        }
    });
});

//generate keys
$('#8bytes').click(function (e) {
    e.preventDefault();
    $('#lab1form-key').val(randHex(16));
});
$('#16bytes').click(function (e) {
    e.preventDefault();
    $('#lab1form-key').val(randHex(32));
});
$('#24bytes').click(function (e) {
    e.preventDefault();
    $('#lab1form-key').val(randHex(48));
});
$('#32bytes').click(function (e) {
    e.preventDefault();
    $('#lab1form-key').val(randHex(64));
});
