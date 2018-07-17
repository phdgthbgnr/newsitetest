
// function DomLoaded(){
    console.log('ajaxcall');
    console.log(with_ajax.url);
    (function($){

        function find_page_number( element ) {
            console.log(element);
            return parseInt( element.html() );
        }

        $(document).on('click','.ajaxcategory',function(evt){
            evt.preventDefault();
            var url = evt.currentTarget.getAttribute('href');
            var to = evt.currentTarget.getAttribute('data-type');
            try{
                var o = JSON.parse(to);
                // if(o === undefined) throw "json not well formed"
            }catch(e){
                console.log(e);
                return false;
            }
            // page = find_page_number( $(this.parent).clone() );

            $.ajax({
                url: with_ajax.url,
                type: 'POST',
                data:{
                    action: 'loadcontent',
                    fullurl: url,
                    queryvars: with_ajax.queryvars,
                    type: o.type,
                    slug: o.slug,
                    page: o.page
                },
                success:function(res){
                    console.log('res',res);
                },
                error:function(err){
                    console.log('error',err);
                }
            });
            return false;
        })
    })(jQuery);
// }


// if (window.addEventListener){
//     window.addEventListener('DOMContentLoaded', function(){DomLoaded()});
// }else if (window.attachEvent){ // IE8
//     window.attachEvent('onload', function() { DomLoaded(); });
// }else{
//     window.onload = DomLoaded();
// };