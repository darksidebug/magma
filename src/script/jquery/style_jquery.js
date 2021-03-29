$(document).ready(function(){

    // $(document).on('click', '.btn__next', function(){
    //     $(this).parents('.belt').toggleClass('is-transitioned_1');
    //     $(this).parents('.belt').removeClass('is-transitioned_2');
    //     $(this).parents('.belt').removeClass('is-transitioned_3');
    //     $(this).parents('.belt').removeClass('is-transitioned_4');
    // });

    // $(document).on('click', '.business_btn__next', function(){
    //     $(this).parents('.belt').toggleClass('is-transitioned_2');
    //     $(this).parents('.belt').removeClass('is-transitioned_4')
    // });

    // $(document).on('click', '.business_btn__previous', function(){
    //     $(this).parents('.belt').toggleClass('is-transitioned_3');
    //     $(this).parents('.belt').removeClass('is-transitioned_1');
    //     $(this).parents('.belt').removeClass('is-transitioned_4');
    // });

    // $(document).on('click', '.btn__previous', function(){
    //     $(this).parents('.belt').toggleClass('is-transitioned_2');
    //     $(this).parents('.belt').removeClass('is-transitioned_2');
    //     $(this).parents('.belt').removeClass('is-transitioned_3');
    // });
    $(document).on('click', '.btn__next', function(){
        $(this).parents('.belt').toggleClass('is-transitioned_1');
        $(this).parents('.belt').removeClass('is-transitioned_2');
    });

    $(document).on('click', '.btn__previous', function(){
        $(this).parents('.belt').toggleClass('is-transitioned_2');
        $(this).parents('.belt').removeClass('is-transitioned_1');
    });
    
});