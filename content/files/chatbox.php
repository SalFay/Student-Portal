<?php
if (empty($_SESSION['std_name']) && empty($_SESSION['user_name'])) {
    return;
}
?>
<div class="chat-wrapper">
    <div class="chat-header" id="chat-head">
        <strong>Chat</strong>
    </div>
    <div id="chatbox" class="chat-body">
        <div class="chat-message-wrapper" id="chat-messages">

        </div>
        <div class="chat-from-wrapper">
            <form method="post" id="chat-form">
                <div class="row">
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="message" name="message" autofocus>
                    </div>
                    <div class="col-sm-3" style="padding:0">
                        <input type="submit" value="Send" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click','#chat-head',function(){
            $("#chatbox").slideToggle();
        });
        $('body').on('submit', '#chat-form',function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo SITE_URL ?>/content/files/chat-submit.php',
                data: $(this).serialize(),
                type: 'POST',
                success: function (data) {
                    $("#message").val('');
                    refresh_chat();
                }
            });
        });
        setInterval(function(){
            refresh_chat();
        },2000);
;
    });

    function refresh_chat(){
        $.ajax({
            url: '<?php echo SITE_URL ?>/content/files/chat-refresh.php',
            success: function (data) {
                $("#chat-messages").html(data);
            }
        });
    }
</script>
