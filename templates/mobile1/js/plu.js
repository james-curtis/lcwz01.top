function StopButton() {
    document.getElementById(arguments[0]).disabled = true;
    document.getElementById(arguments[0]).value = "发表(" + arguments[1] + ")";
    if (--arguments[1] > 0) {
        window.setTimeout("StopButton('" + arguments[0] + "'," + arguments[1] + ")", 1000);
    }
    if (arguments[1] <= 0) {
        document.getElementById(arguments[0]).value = '发表';
        document.getElementById(arguments[0]).disabled = false;
    }
}

function GetComment($ID, $Page) {
    $.ajax({
        url: "Comment.aspx?action=ajax_getcomment&id=" + $ID + "&page=" + $Page + "&time" + new Date().toString(),
        type: 'GET',
        success: function() {
            $('#comment').html(arguments[0]);
        }
    });
}

function SendComment($CommentParentID) {
    var $CommentText = $('#CommentText').val();
    if ($.trim($CommentText) == '') {
        alert('请您填写评论内容！');
        $('#CommentText').focus();
        return false;
    }
    if ($CommentText.length < 5 || $CommentText.length > 200) {
        alert('内容必须在5-200字之间！');
        return false;
    }
    StopButton('submitForm', 10);
    $.ajax({
        url: "Comment.aspx?action=ajax_sendcomment&commentparentid=" + $CommentParentID + "&commenttext=" + escape($CommentText) + "&time=" + new Date().toString(),
        type: 'GET',
        success: function() {
            alert(arguments[0]);
            $("#CommentText").val("");
        }
    });
}