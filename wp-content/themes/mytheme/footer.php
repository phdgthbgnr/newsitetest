    </div><!-- #main -->
        <footer id="colophon" class="site-footer" role="contentinfo">
                
        </footer><!-- #colophon -->
    
    </div><!-- #page -->

    <?php 
        wp_footer(); 
    /*
    $current_user = wp_get_current_user();
        print_r(get_currentuserinfo());
        print_r($current_user);
        print_r($current_user->roles);
        */
    ?>

    <script>

        (function(){
            // chargement bootsrtap
            var deferring = [
                '<?php echo get_template_directory_uri() ?>/bootstrap412/css/bootstrap.min.css',
                '<?php echo get_template_directory_uri() ?>/bootstrap412/js/bootstrap.min.js'
            ],
            
            _i=0,

            initAfter = function(){
                console.log('css et js chargés')
            },

            downloadJSAtOnload = function(arr, callback) {
                // charge JS et CSS
                var t = arr.length-1;
                if (arr[_i].match('^(.*\.js)')){
                var element = document.createElement('script');
                    element.setAttribute('type','text/javascript');
                    element.setAttribute('src',arr[_i]);
                    if (element.addEventListener != undefined){
                    element.addEventListener('load',function(e){
                        _i++;
                        if(_i <= t) downloadJSAtOnload(arr, callback);
                        if(_i > t) {
                            callback();
                        }
                    });
                    }else if (element.readyState){ // IE8
                        element.onreadystatechange = function(){
                            if(element.readyState == 'loaded' || element.readyState == 'complete') {
                                _i++;
                            if(_i <= t) downloadJSAtOnload(arr, callback);
                                if(_i > t) {
                                    callback();
                                }
                            }
                        }
                    }
                    if(_i <= t) document.body.appendChild(element);
                };
                // chargement CSS
                if (arr[_i].match('^(.*\.css)$')){
                    // loadStylesheet(deferjs[i]);
                    if (document.createStyleSheet){
                        document.createStyleSheet(arr[_i]);
                    }else {
                        var stylesheet = document.createElement('link');
                        stylesheet.href = arr[_i];
                        stylesheet.rel = 'stylesheet';
                        stylesheet.type = 'text/css';
                        document.getElementsByTagName('head')[0].appendChild(stylesheet);
                    }
                    _i++;
                    if(_i <= t) downloadJSAtOnload(arr, callback);
                    //if(i > t) window.myConsole('CSS chargées');
                }
            },

            DomLoaded = function(e){
                // if(ie9) deferjs1.push('_js/pointereventspolyfill.js');
                downloadJSAtOnload(deferring, initAfter);
            };

            if (window.addEventListener){
                window.addEventListener('DOMContentLoaded', function(){DomLoaded()});
            }else if (window.attachEvent){ // IE8
                window.attachEvent('onload', function() { DomLoaded(); });
            }else{
                window.onload = DomLoaded();
            };
        })()

    </script>
</body>
</html>