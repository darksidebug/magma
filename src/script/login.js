$(document).ready(function(){
    $('#login').click(function(event){
        event.preventDefault();
        const login = 'users';
        const email = $('#email').val();
        const password = $('#password').val();
        if(email != '' && password != ''){
            $.ajax({
                url: '<?php echo base_url("User/login"); ?>',
                type: 'POST',
                data: {
                    email    : email,
                    password : password,
                    login    : login
                },
                success:function(data){
                    const log_oObj = JSON.parse(data);
                    if(log_oObj.logged_in == true){
                        setTimeout(function(){
                            window.location.href = '<?php echo base_url("pages/main"); ?>';
                        },
                        1000);
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
                "Fields Error",
                "Email and Password fields are both needed",
                "error"
            );
        }
    });
});