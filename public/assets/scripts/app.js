!function(){"use strict";angular.module("Journal",["journal.config","journal.routes","journal.run","journal.constant","journal.component.deletePostModal","journal.component.login","journal.component.header","journal.component.editor","journal.component.installer","journal.component.installerDetails","journal.component.installerStart","journal.component.installerSuccess","journal.component.postLists","journal.component.settings","journal.component.settingsModal","journal.component.sidebar","journal.component.userCreate","journal.component.userLists","journal.component.userProfile","journal.component.userProfileModal","journal.shared.auth","journal.shared.fileUploader","journal.shared.storage","journal.shared.toastr","journal.shared.markdownConverter"]),angular.module("journal.config",["LocalStorageModule","toastr"]),angular.module("journal.constant",[]),angular.module("journal.routes",["ui.router"]),angular.module("journal.run",["journal.shared.auth","ngProgressLite"]),angular.module("journal.component.deletePostModal",["journal.constant","journal.shared.auth","journal.shared.toastr","ui.bootstrap","ui.router"]),angular.module("journal.component.login",["journal.constant","journal.shared.auth","journal.shared.toastr","ui.router"]),angular.module("journal.component.header",["journal.shared.auth","ui.bootstrap"]),angular.module("journal.component.editor",["journal.constant","journal.shared.auth","journal.shared.markdownConverter","journal.shared.toastr","ngFileUpload","ngSanitize","ui.bootstrap","ui.codemirror","ui.router"]),angular.module("journal.component.installer",["ui.router"]),angular.module("journal.component.installerStart",["ui.router"]),angular.module("journal.component.installerDetails",["journal.constant","journal.shared.auth","journal.shared.toastr","ui.router"]),angular.module("journal.component.installerSuccess",["journal.shared.auth","journal.shared.toastr"]),angular.module("journal.component.postLists",["journal.constant","journal.shared.auth","journal.shared.toastr","journal.shared.markdownConverter","ui.router"]),angular.module("journal.component.settings",["journal.constant","journal.shared.auth","journal.shared.toastr","ui.bootstrap","ui.router"]),angular.module("journal.component.settingsModal",["journal.constant","journal.component.settings","journal.shared.auth","journal.shared.fileUploader","journal.shared.toastr","ngFileUpload","ui.bootstrap","ui.router"]),angular.module("journal.component.sidebar",["journal.shared.auth","ui.bootstrap","ui.router"]),angular.module("journal.component.userCreate",["journal.constant","journal.shared.auth","journal.shared.toastr","ui.router"]),angular.module("journal.component.userLists",["journal.constant","journal.shared.auth","journal.shared.toastr","ui.router"]),angular.module("journal.component.userProfile",["journal.constant","journal.shared.auth","journal.shared.toastr","ui.bootstrap","ui.router"]),angular.module("journal.component.userProfileModal",["journal.component.userProfile","journal.shared.fileUploader","journal.shared.toastr","ngFileUpload","ui.bootstrap","ui.router"]),angular.module("journal.shared.auth",["journal.constant","journal.shared.storage"]),angular.module("journal.shared.fileUploader",["journal.constant","journal.shared.auth","ngFileUpload"]),angular.module("journal.shared.toastr",["ngAnimate","toastr"]),angular.module("journal.shared.storage",["LocalStorageModule"]),angular.module("journal.shared.markdownConverter",["ngSanitize"])}(),function(){"use strict";function t(t,e){var a="/assets/templates";e.otherwise("/").when("/","/post/lists").when("/post","/post/lists").when("/installer","/installer/start").when("/user","/user/lists"),t.state("editor",{url:"/editor",views:{"":{templateUrl:a+"/editor/editor.html"},header_content:{templateUrl:a+"/header/header.html"},sidebar_content:{templateUrl:a+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("landing",{url:"/",authenticate:!0,installer:!1}).state("login",{url:"/login",templateUrl:a+"/login/login.html",authenticate:!1,installer:!1}).state("installer",{url:"/installer",templateUrl:a+"/installer/installer.html",authenticate:!1,installer:!0,"abstract":!0}).state("installer.start",{url:"/start",views:{installer_content:{templateUrl:a+"/installer-start/installer-start.html"}}}).state("installer.details",{url:"/details",views:{installer_content:{templateUrl:a+"/installer-details/installer-details.html"}},authenticate:!1,installer:!0}).state("installer.success",{url:"/success",views:{installer_content:{templateUrl:a+"/installer-success/installer-success.html"}},authenticate:!1,installer:!0}).state("post",{url:"/post","abstract":!0,views:{"":{templateUrl:a+"/post/post.html"},header_content:{templateUrl:a+"/header/header.html"},sidebar_content:{templateUrl:a+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("post.lists",{url:"/lists",views:{post_content:{templateUrl:a+"/post-lists/post-lists.html"}},authenticate:!0,installer:!1}).state("postEditor",{url:"/editor/:postId",views:{"":{templateUrl:a+"/editor/editor.html"},header_content:{templateUrl:a+"/header/header.html"},sidebar_content:{templateUrl:a+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("settings",{url:"/settings",views:{"":{templateUrl:a+"/settings/settings.html"},header_content:{templateUrl:a+"/header/header.html"},sidebar_content:{templateUrl:a+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("user",{url:"/user",views:{"":{templateUrl:a+"/user/user.html"},header_content:{templateUrl:a+"/header/header.html"},sidebar_content:{templateUrl:a+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1,"abstract":!0}).state("user.lists",{url:"/lists",views:{user_content:{templateUrl:a+"/user-lists/user-lists.html"}},authenticate:!0,installer:!1}).state("user.create",{url:"/create",views:{user_content:{templateUrl:a+"/user-create/user-create.html"}},authenticate:!0,installer:!1}).state("user.profile",{url:"/profile/:userId",views:{user_content:{templateUrl:a+"/user-profile/user-profile.html"}},authenticate:!0,installer:!1})}angular.module("journal.routes").config(["$stateProvider","$urlRouterProvider",t])}(),function(){"use strict";function t(t){angular.extend(t,{autoDismiss:!1,containerId:"toast-container",maxOpened:5,newestOnTop:!1,positionClass:"toast-top-right",preventDuplicates:!1,preventOpenDuplicates:!0,target:"body",timeOut:1e4})}function e(t){t.defaults.useXDomain=!0,delete t.defaults.headers.common["X-Requested-With"]}function a(t){t.setPrefix("journal")}angular.module("journal.config").config(["$httpProvider",e]).config(["toastrConfig",t]).config(["localStorageServiceProvider",a])}(),function(){"use strict";function t(t,e,a,r,n){t.$on("$stateChangeStart",function(t,a,o,l,s){n.start(),a.installer||!a.authenticate||r.user()||(e.transitionTo("login"),t.preventDefault()),("installer"==a.name||"installer.start"==a.name)&&r.checkInstallation().success(function(a){return a.installed?(e.transitionTo("login"),void t.preventDefault()):void 0}),"login"==a.name&&r.user()&&r.getToken()&&(e.transitionTo("post.lists"),t.preventDefault())}),t.$on("$stateChangeSuccess",function(t,e,r,o,l){a(function(){n.done()})})}function e(t,e){return e.checkInstallation().success(function(e){return e.installed?void 0:(t.transitionTo("installer"),void event.preventDefault())}),e.getToken()||e.user()?void(e.getToken()&&e.checkToken().success(function(t){t.user&&e.login(t.user)}).error(function(a){e.logout(),t.transitionTo("login"),event.preventDefault()})):(e.logout(),t.transitionTo("login"),void event.preventDefault())}angular.module("journal.run").run(["$rootScope","$state","$timeout","AuthService","ngProgressLite",t]).run(["$state","AuthService",e])}(),function(){"use strict";angular.module("journal.constant").constant("CONFIG",{API_URL:"/api/v1.0",DEFAULT_AVATAR_URL:"http://40.media.tumblr.com/7d65a925636d6e3df94e2ebe30667c29/tumblr_nq1zg0MEn51qg6rkio1_500.jpg",DEFAULT_COVER_URL:"/assets/images/wallpaper.jpg",VERSION:"1.0.0"})}();