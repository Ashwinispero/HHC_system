var $AVAYA_INCOMING_CALL_FLAG = 1;
var $Avaya_Incoming_Call_Timer = null;
$(window).load(function () {
    $Avaya_Incoming_Call_Timer = setInterval(avaya_change_incoming_call, 5000);
});
function avaya_change_incoming_call() {
    if ($AVAYA_INCOMING_CALL_FLAG == 1) {
        $.get( base_url+'calls/avaya_get_incoming_calls',function($data){
        $data = JSON.parse($data);
        if($data.length != 0){
        $('a#mt_atnd_calls').click();
        setTimeout(function(){
                     console.log($data);
                     $('#caller_no').val($data.m_no);
                     $('#clr_ext_no_val').val($data.ext_no);
                },1000);
                 $AVAYA_INCOMING_CALL_FLAG = 0;
            }
        });
        
                      
        $('a.avaya_incoming_call_refresh').click();
    }
}

function avaya_start_incoming_call() {
    $AVAYA_INCOMING_CALL_FLAG = 1;
}