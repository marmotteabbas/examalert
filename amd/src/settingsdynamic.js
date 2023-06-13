define(['jquery'], function() {
    var set_input = function() {
        if(document.getElementById('id_s_local_examalert_datemode').checked ) {
            document.getElementById('id_s_local_examalert_datebegin').readOnly = true;
            document.getElementById('id_s_local_examalert_dateend').readOnly = true;
        }

        $( "#id_s_local_examalert_datemode" ).on( "click", function() {
            if(document.getElementById('id_s_local_examalert_datemode').checked ) {
                document.getElementById('id_s_local_examalert_datebegin').readOnly = true;
                document.getElementById('id_s_local_examalert_dateend').readOnly = true;
            } else {
                document.getElementById('id_s_local_examalert_datebegin').readOnly = false;
                document.getElementById('id_s_local_examalert_dateend').readOnly = false;
            }
        } );
    };
    return {
        set_input: set_input
    };
});