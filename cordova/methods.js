var methods = {

    initialize: function() {
        window.localStorage['tryLogin'] = 0;

        app.methods.setupGeolocation();
        app.methods.setupBackground();
        // app.methods.setupPush();

        app.request.setup({
            headers: {
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache',
                'Access-Control-Allow-Origin': '',
                'Access-Control-Allow-Headers': '',
                'Access-Control-Allow-Methods': '*',
                // 'Auth-key': (isIos ? 'W8BF9AIFs9DzgKPgxAfQ-x-fF2DP-yRN' : 'wmHvkxCwORoQPPqMXl8qLXlrX1UrOoek'),
                'Authorization': 'Bearer ' + (isIos ? 'W8BF9AIFs9DzgKPgxAfQ-x-fF2DP-yRN' : 'wmHvkxCwORoQPPqMXl8qLXlrX1UrOoek'),
            }
        });
    },

    /** Ovverride back button functional */
    onBackKeyDown: function(e) {
        e.preventDefault();
        e.stopPropagation();
        var leftp = app.panel.left && app.panel.left.opened;
        var rightp = app.panel.right && app.panel.right.opened;

        if (leftp || rightp) {
            app.panel.close();
            return false;
        } else if ($$('.modal-in').length > 0) {
            app.dialog.close();
            app.popup.close();
            return false;
        } else if (app.views.main.router.url == '/home/') {
            app.dialog.confirm("Are you sure you want to logout?", function() {
                app.methods.signOut();
            });
        } else if (app.views.main.router.url == '/') {
            app.dialog.confirm("Are you sure you want to exit?", function() {
                // var deviceType = device.platform;
                // if (deviceType == "Android" || deviceType == "android") {
                BackgroundGeolocation.stop();
                navigator.app.exitApp();
                // }
            });
        } else {
            homeView.router.back();
        }
    },

    /** Event of application return from background */
    onResume: function() {
        BackgroundGeolocation.checkStatus(function (status) {
            console.log('[INFO] BackgroundGeolocation service is running', status.isRunning);
            console.log('[INFO] BackgroundGeolocation services enabled', status.locationServicesEnabled);
            console.log('[INFO] BackgroundGeolocation auth status: ' + status.authorization);
        
            // you don't need to check status before start (this is just the example)
            if (!status.isRunning) {
                BackgroundGeolocation.start(); //triggers start on start event
            }
        });
    },

    /** Quite from personal area, logout */
    signOut: function() {
        window.localStorage.removeItem('loginForm.password');
        app.methods.initialize();
        homeView.router.clearPreviousHistory();
        homeView.router.navigate({ name: 'index' });
        clearInterval(app.data.loggerId);
    },

    /** Set current page for have the same data without additional queries for API */
    setCurrentPage: function(page) {
        app.data.currentPage = page;
        app.methods.updatePage();
    },

    updatePage: function() {
        if (app.data.currentPage) {
            app.data.currentPage.$setState({
                is_working: app.data.is_working,
                user: app.data.user,
                areas: app.data.areas,
                statistic: app.data.statistic
            });
        }
    },

    /** on first run, try auto username by previous data */
    autoLogin: function() {
        if (typeof window.localStorage['loginForm.username'] !== 'undefined' &&
            typeof window.localStorage['loginForm.password'] !== 'undefined' &&
            window.localStorage['tryLogin'] == 0
        ) {
            var loginData = {
                'username': window.localStorage['loginForm.username'],
                'password': window.localStorage['loginForm.password'],
            };

            app.preloader.show();
            app.request.post(app.methods.getApiUrl('site/login'), loginData, function(response) {
                var auth = JSON.parse(response);
                app.methods.authClient(auth);
            }, function(data, code) {
                // app.methods.alertApiError
                // not show any arrors
                console.log('code: ', code);
                console.log('response: ', data.response);
                app.preloader.hide();
            });
        } else {
            // app.loginScreen.open('#start-screen');
            homeView.router.navigate({ name: 'index' });
        }
        window.localStorage['tryLogin'] = 1;
    },

    uploadAreas: function() {
        app.request.get(app.methods.getApiUrl('area?expand=campains'), function(response) {
            var areas = JSON.parse(response);
            if (app.data.areas.length > 0) {
                app.data.areas.forEach(function(area) {
                    if (area.polygon) {
                        area.polygon.remove();
                    }
                });
            }
            app.data.areas = areas;
            app.methods.updatePage();
            if (app.data.loggerCounter && app.data.currentPage && typeof app.data.currentPage.renderAreas === "function") {
                app.data.currentPage.renderAreas();
            }
        }, app.methods.alertApiError);
    },

    /** Send statistic for drivers and update user data for everyone */
    updateStatistic: function() {
        var data = {};
        if (app.data.user.role === ROLE_DRIVER) {
            data = {
                "is_working": app.data.is_working,
                "latLng": {
                    "lat": app.data.location.latitude,
                    "lng": app.data.location.longitude
                },
                'settings': app.data.settings,
                'quest_id': 0,
                'hardware_status': -1,
                'image_hash': ''
            };

            if (typeof data.settings != 'undefined') {
                data.settings = app.methods.clearFormData(data.settings);
            }
        }

        app.request.post(app.methods.getApiUrl('site/statistic'), data, function(response) {
            var statistic = JSON.parse(response);
            if (typeof statistic.balance !== 'undefined') {
                app.data.user.balance = statistic.balance;
                delete statistic.balance;
            }
            app.data.statistic = statistic;
            app.methods.updatePage();
        }, app.methods.alertApiError);

        if (app.data.loggerCounter % 5 === 0) {
            app.methods.uploadAreas();
        }
        app.data.loggerCounter++;
    },

    /** Login client in APP  */
    authClient: function(authKey) {
        app.data.authKey = authKey;
        app.request.setup({
            headers: {
                'Authorization': 'Bearer ' + authKey
            }
        });

        app.request.get(app.methods.getApiUrl('user/my'), function(response) {
            var user = JSON.parse(response);
            app.data.user = user;
            setTimeout(function() {
                app.methods.updateStatistic();
                app.data.loggerId = setInterval(app.methods.updateStatistic, app.data.INTERVAL);
                homeView.router.navigate({ name: 'home' });
                app.preloader.hide();
            }, 2000);
        }, app.methods.alertApiError);
    },

    /** Alert messages and API errors */
    alertApiError: function(data, code) {
        // app.methods.onLocationError(data);
        var response = JSON.parse(data.response);

        if (code == 500 || code == 403) { // server error exception
            if (typeof response.message == 'string' && typeof response.name == 'string') {
                app.dialog.alert(response.message, response.name);
            } else {
                app.dialog.alert(data.response);
            }
        } else if (code == 422) { // data validation
            var text = '';
            response.forEach(function(item) {
                text += item.message + '<br>';
            });
            app.dialog.alert(text, data.statusText);
        } else {
            app.dialog.alert('Unknown error, please let us know #' + code + ' <a href="mailto:support@digitaladsonvehicles.com" class="external">support@digitaladsonvehicles.com</a>');
        }
        app.preloader.hide();
    },

    /** Helper for get url of API  */
    getApiUrl: function(action) {
        return "https://digitaladsonvehicles.com/api/" + action;
    },

    /** Push-up notifications  */
    setupPush: function() {
        var push = PushNotification.init({
            "android": {
                "vibrate": true,
                "sound": true,
                "forceShow": true,
            },
            "ios": {
                "sound": true,
                "vibration": true,
                "badge": true
            },
        });

        push.on('registration', function(data) {
            var oldRegId = window.localStorage.getItem('registrationId');
            if (oldRegId !== data.registrationId) {
                window.localStorage.setItem('registrationId', data.registrationId);
                // @notice: Post registrationId with current location and status
            }
        });

        push.on('error', app.methods.onLocationError);

        push.on('notification', function(data) {
            if (data.additionalData.foreground) {
                push.clearAllNotifications();
            } else if (isAndroid && data.additionalData.coldstart &&
                       cordova.plugins.backgroundMode.isActive()
            ) {
                cordova.plugins.backgroundMode.moveToForeground();
                push.clearAllNotifications();
            }

            if (!data.additionalData.coldstart && !data.additionalData.foreground) {
                return false;
            }
        });
    },

    /** Background mode supporting for send dav log and work around notifications */
    setupBackground: function() {
        cordova.plugins.backgroundMode.setDefaults({
            title: 'Working in background',
            text: 'DAV application will be work in background untill your status is active.',
            hidden: false,
            silent: true
        });
        cordova.plugins.backgroundMode.on('enable', () => {
            if (cordova.plugins.backgroundMode.disableWebViewOptimizations &&
                typeof cordova.plugins.backgroundMode.disableWebViewOptimizations === 'function'
            ) {
                cordova.plugins.backgroundMode.disableWebViewOptimizations();
            }
            if (cordova.plugins.backgroundMode.disableBatteryOptimizations &&
                typeof cordova.plugins.backgroundMode.disableBatteryOptimizations === 'function'
            ) {
                cordova.plugins.backgroundMode.disableBatteryOptimizations();
            }
        });
    },

    /** Geolacation navigation with supporting background mode */
    setupGeolocation: function() {
        BackgroundGeolocation.configure({
            locationProvider: BackgroundGeolocation.ACTIVITY_PROVIDER,
            desiredAccuracy: BackgroundGeolocation.HIGH_ACCURACY,
            desiredAccuracy: 10,
            stationaryRadius: 20,
            distanceFilter: 30,
            startOnBoot: false,
            startForeground: false,
            stopOnTerminate: true,   // Not Allow the background-service to continue tracking when user closes the app.
            notificationTitle: 'DAV uses your GPS location for accuracy.',
            notificationText: '',
            debug: false,
            interval: 10000,
            fastestInterval: 3000
        });

        BackgroundGeolocation.on('location', app.methods.onChangeLocation);
        BackgroundGeolocation.on('stationary', app.methods.onChangeLocation);
        BackgroundGeolocation.on('error', app.methods.onLocationError);
        BackgroundGeolocation.on('foreground', app.methods.onChangeLocation);
        BackgroundGeolocation.start();
    },

    /** Navigation update and error catch */
    onChangeLocation: function(position) {
        if (position && position.coords) { // For supporting default navigator.geolocation.watchPosition
            position = position.coords;
            position.bearing = position.heading;
        }

        if (position.latitude && position.longitude) {
            app.data.location = position;

            if (app.data.currentPage && typeof app.data.currentPage.onChangeLocation === "function") {
                app.data.currentPage.onChangeLocation(position);
            }
        }
    },

    onLocationError: function(error) {
        console.log(JSON.stringify(error, null, 4));
    },

    displayTimeFormat: function(values, displayValues) {
        if (!Array.isArray(values)) {
            values = values.split(/ |:/);
            if (values.length == 3 && values[2] == '00') {
                values = values.slice(0, 2);
            }
            if (values.length == 2) {
                if (values[0] >= 12) {
                    values[0] -= 12;
                    values.push('p.m.');
                } else {
                    values.push('a.m.');
                }
            }
        }
        return values[0] + ':' + values[1] + ' ' + values[2];
    },
    prepareTimeFormat: function(time) {
        var datetime = time.split(/ |:/);
        if (datetime[2] == 'a.m.') {
            return datetime[0] + ':' + datetime[1];
        }
        datetime[0] = parseInt(datetime[0]) + 12;
        return datetime[0] + ':' + datetime[1];
    },

    initAllSelect: function(smartSelect) {
        smartSelect.vl.$el.find('input').on('change', function(event) {
            var allValues = [];
            if ($$(this).val() == 'all') {
                if ($$(this).prop('checked')) {
                    smartSelect.items.forEach(function(item) {
                        allValues.push(item.value);
                    });
                }
                smartSelect.vl.$el.find('input').prop('checked', $$(this).prop('checked'));
            } else {
                var isAllChecked = false;
                smartSelect.vl.$el.find('input:checked').forEach(function(el) {
                    if ($$(el).val() == 'all') {
                        isAllChecked = true;
                    } else {
                        allValues.push($$(el).val());
                    }
                });

                if (isAllChecked) {
                    if (smartSelect.items.length !== smartSelect.vl.$el.find('input:checked').length) {
                        smartSelect.vl.$el.find('input[value="all"]').prop('checked', false);
                    }
                } else {
                    if (smartSelect.items.length - 1 === smartSelect.vl.$el.find('input:checked').length) {
                        smartSelect.vl.$el.find('input[value="all"]').prop('checked', true);
                        allValues.push('all');
                    }
                }
            }
            smartSelect.setValue(allValues);
        });
    },
    clearFormData: function(data) {
        if (typeof data.area_ids != 'undefined') {
            data.area_ids = app.methods.clearFromAll(data.area_ids);
        }
        if (typeof data.category_ids != 'undefined') {
            data.category_ids = app.methods.clearFromAll(data.category_ids);
        }
        return data;
    },
    clearFromAll: function(arr) {
        var index = arr.indexOf('all');
        if (index !== -1) {
            arr.splice(index, 1);
        }
        return arr;
    },
};
