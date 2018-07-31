(function($){

    $(document).ready(function(){
        $(document).on('click','.realisationsnav',function(evt){
            evt.preventDefault();
            console.log(evt.currentTarget.getAttribute('data-link'));
            window.location.href = evt.currentTarget.getAttribute('data-link');
        });
    })

})(jQuery)