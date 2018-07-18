
// function DomLoaded(){
    console.log('ajaxcall');
    console.log(with_ajax.url);
    (function($){

        // function find_page_number( element ) {
        //     console.log(element);
        //     return parseInt( element.html() );
        // }

        $(document).ready(function(){

            // chargement SINGLE POST ---------------------------------------------------------------------------
            $(document).on('click','.ajaxpost',function(evt){
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
                        $(to.id_contener).empty().append(res);
                    },
                    error:function(err){
                        console.log('error',err);
                    }
                });
                return false;
            });
            // -------------------------------------------------------------------------------------------

            // chargement POSTS / CATEGORIES ---------------------------------------------------------------------
            $(document).on('click','.ajaxcategory', function(evt){
                console.log('category');
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
                
                console.log(o.slug);
                $.ajax({
                    url: with_ajax.url,
                    type: 'POST',
                    data:{
                        action: 'loadcontent-posts',
                        fullurl: url,
                        queryvars: with_ajax.queryvars,
                        type: o.type,
                        slug: o.slug,
                        parent: o.parent
                    },
                    success:function(res){
                        // console.log('res',res);
                        $(o.id_contener).empty().append(res);
                    },
                    error:function(err){
                        console.log('error',err);
                    }
                });
                return false;
            })
        });

        $('[data-toggle="popover"]').popover(); 
        
    })(jQuery);
// }


// if (window.addEventListener){
//     window.addEventListener('DOMContentLoaded', function(){DomLoaded()});
// }else if (window.attachEvent){ // IE8
//     window.attachEvent('onload', function() { DomLoaded(); });
// }else{
//     window.onload = DomLoaded();
// };