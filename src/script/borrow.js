$(document).ready(function(){
    $('#save').click(function(event){
        event.preventDefault();
        // const login = 'users';
        const name = $('#name').val();
        // const password = $('#password').val();
        if(name != ''){
            $.ajax({
                url: '<?php echo base_url("User/borrow"); ?>',
                type: 'POST',
                data: {
                    name    : name
                    // password : password,
                    // login    : login
                },
                success:function(data){
                    const log_oObj = JSON.parse(data);
                    if(log_oObj.save == true){
                        // setTimeout(function(){
                        //     window.location.href = '<?php echo base_url("pages/main"); ?>';
                        // },
                        // 1000);
                        alert();
                        // swal(
                        //     'Succesfull',
                        //     log_oObj.msg,
                        //     'success'
                        // );
                    }
                    else{
                        swal(
                            'No Match Found',
                            log_oObj.msg,
                            'error'
                        );
                    }
                }
            });
        }
        else{
            swal(
                "Empty field(s)",
                "All fields are required.",
                "error"
            );
        }
    });
});