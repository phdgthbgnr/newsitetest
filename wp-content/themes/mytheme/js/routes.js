// console.log('crossroads',crossroads);
(function () {

    function loadCategory(url, action, type, slug, elemt) {
        console.log('loadCategory');
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                action: action,
                type: type,
                slug: slug
            },
            success: function (res) {
                $(elemt).empty().append(res);
            },
            error: function (err) {
                console.log('error', err);
            }
        });
    }

    function loadPost(url, action, type, slug, post, elemt) {
        console.log('loadPost');
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                action: action,
                type: type,
                slug: elemt,
                post: post
            },
            success: function (res) {
                $(elemt).empty().append(res);
            },
            error: function (err) {
                console.log('error', err);
            }
        });
    }

    function loadPage(url, action, type, slug, elemt) {
        console.log('loadPage');
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                action: action,
                type: type,
                slug: slug
            },
            success: function (res) {
                console.log('success ', res);
                $(elemt).empty().append(res);
            },
            error: function (err) {
                console.log('error ', err);
            }
        });
    }

    // front page
    crossroads.addRoute('/', function () {
        console.log('root');
        // load homepage
        loadPage(router_ajax.url, 'loadcontent-page', 'frontpage', '', '#content');
    })

    // all categories
    crossroads.addRoute('/{root}/', function (root) {
        console.log('{root}', root);

    })

    // by category
    crossroads.addRoute('/{rootCategory}/{category}/', function (rootCategory, category) {
        // console.log(rootCategory);
        // console.log(category);
        if (rootCategory === 'realisations') loadCategory(router_ajax.url, 'loadcontent-posts', 'category_name', category, '#content');
    });

    // by category/post
    crossroads.addRoute('/{rootCategory}/{category}/{post}/', function (rootCategory, category, post) {
        if (rootCategory === 'realisations') loadPost(router_ajax.url, 'loadcontent-post', 'category_name', category, 'name', post, '#content');
    });

    crossroads.bypassed.add(function (request) {
        console.error(request + ' seems to be a dead end...');
    });

    //Listen to hash changes
    window.addEventListener("hashchange", function () {
        var route = '/';
        var hash = window.location.hash;
        if (hash.length > 0) {
            route = hash.split('#').pop();
        }
        console.log(' route : ', route);
        crossroads.parse(route);
    });

    // trigger hashchange on first page load
    window.dispatchEvent(new CustomEvent("hashchange"));

})();