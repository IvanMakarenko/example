// Dom7
var $$ = Dom7;

var isAndroid = Framework7.prototype.device.android === true;
var isIos = Framework7.prototype.device.ios === true;
var ROLE_DRIVER         = "driver";
var ROLE_ADVERTISER     = "advertiser";
var STATUS_FINISHED     = 8;
var STATUS_INACTIVE     = 9;
var STATUS_ACTIVE       = 10;
var STATUS_CONFIRMED    = 11;
var STATUS_WAIT_CONFIRM = 12;
var MAP_OPTIONS         = {
    'controls': {
        'compass': false,
        'myLocationButton': false,
        'myLocation': false,    // (blue dot)
        'indoorPicker': true,
        'zoom': false,          // android only
        'mapToolbar': false     // android only
    },
};
var MAP_WITHOUT_GESTURES = MAP_OPTIONS;
MAP_WITHOUT_GESTURES.gestures = {
    'scroll': false,
    'tilt': false,
    'rotate': false,
    'zoom': false
};

// Framework7 App main instance
var app  = new Framework7({
    root: '#app',
    id: 'com.dav.dav',
    name: 'DAV',
    pushState: true,
    material: true, // isAndroid ? true : false,

    panel: {
        swipeNoFollow: true,
    },

    navbar: {
        iosCenterTitle: true,
        mdCenterTitle: true,
        auroraCenterTitle: true,
        collapseLargeTitleOnScroll: false,
    },

    data: function() {
        return {
            /** params for logger */
            INTERVAL: 30 * 1000, // 30 seconds
            loggerId: false,
            loggerCounter: 0,

            /** params for render pages */
            is_working: 0,
            user: false,
            statistic: false,
            currentPage: false,

            /** Params of current navigation  */
            location: {
                latitude: false,
                longitude: false
            },

            categories: [
                {
                    "id": 1,
                    "name": "Arts & Crafts",
                },
                {
                    "id": 2,
                    "name": "Automotive",
                },
                {
                    "id": 3,
                    "name": "Baby",
                },
                {
                    "id": 4,
                    "name": "Fashion",
                },
                {
                    "id": 5,
                    "name": "Beauty & Personal Care",
                },
                {
                    "id": 6,
                    "name": "Home & Household",
                },
                {
                    "id": 7,
                    "name": "Books",
                },
                {
                    "id": 8,
                    "name": "Electronics",
                },
                {
                    "id": 9,
                    "name": "Software",
                },
                {
                    "id": 10,
                    "name": "Sports & Outdoors",
                },
                // {
                //     "id": 11,
                //     "name": "Social",
                // },
            ],

            areas: false,
        };
    },

    /** App root methods  */
    methods: methods,
    /** App routes  */
    routes: routes,
});

// Init/Create views
var homeView = app.views.create('#view-index', {
    url: '/'
});

// Handle Cordova Device Ready Event
$$(document).on('deviceready', function() {
    if (isAndroid) {
        StatusBar.styleBlackOpaque(); // @bug: in iOS, don`t change background color.
    }
    document.addEventListener('backbutton', app.methods.onBackKeyDown, false);
    document.addEventListener('resume', app.methods.onResume, false);
    plugin.google.maps.environment.setEnv({
        'API_KEY_FOR_BROWSER_RELEASE': '***',
        'API_KEY_FOR_BROWSER_DEBUG': '***'
    });

    // Init default headers
    app.methods.initialize();
    app.methods.autoLogin();
});

app.on('pageInit', function(page) {
    $$('.sign-out').on('click', app.methods.signOut);

    if (app.data.user == null || app.data.user.role == ROLE_DRIVER) {
        $$('.hidde-for-driver').addClass('hidden');
    } else {
        $$('.hidde-for-driver').removeClass('hidden');
    }
    if (app.data.user == null || app.data.user.role == ROLE_ADVERTISER) {
        $$('.hidde-for-advertiser').addClass('hidden');
    } else {
        $$('.hidde-for-advertiser').removeClass('hidden');
    }

    if (app.data.user == null || app.data.user.status >= STATUS_CONFIRMED) {
        $$('a[href="/campain/"]').removeClass('dissabled');
        $$('a[href="/income/"]').removeClass('dissabled');
    } else {
        $$('a[href="/campain/"]').addClass('dissabled');
        $$('a[href="/income/"]').addClass('dissabled');
    }

    if (app.data.is_working) {
        $$('.for-active-driver').removeClass('hidden');
    }
});

Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};