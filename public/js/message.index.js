/**
 * Created by kamil on 06.10.16.
 */
(function ($) {
    $(document).ready(function () {
        var form = $('[name="message"]');
        form.on('submit', function (event) {
            var messageContent = $('#content').val();
            event.preventDefault();
            $.ajax(
                {
                    'url': '/message/save',
                    'method': 'POST',
                    'data': {"content": messageContent},
                    'success': function (data) {
                        $('#content').val('');
                    }
                });
        });
    });
    setInterval(function(){
        $.ajax(
            {
                'url': '/message/refresh',
                'method': 'POST',
                'success': function (data) {
                    $('#messagesContainer').html(data);
                }
            });
    },1000);
})($)