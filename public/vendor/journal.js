!function(){"use strict";angular.module("Journal",["journal.config","journal.routes","journal.run","journal.constant","journal.component.deletePostModal","journal.component.login","journal.component.markdownHelperModal","journal.component.header","journal.component.editor","journal.component.installer","journal.component.installerDetails","journal.component.installerStart","journal.component.installerSuccess","journal.component.postLists","journal.component.services","journal.component.settings","journal.component.settingsModal","journal.component.sidebar","journal.component.userCreate","journal.component.userLists","journal.component.userProfile","journal.component.userProfileModal","journal.shared.auth","journal.shared.buttonLoader","journal.shared.fileUploader","journal.shared.storage","journal.shared.toastr","journal.shared.markdownConverter","angularMoment"]),angular.module("journal.config",["LocalStorageModule","toastr"]),angular.module("journal.constant",[]),angular.module("journal.routes",["ui.router","journal.constant"]),angular.module("journal.run",["journal.shared.auth","ngProgressLite"]),angular.module("journal.component.deletePostModal",[]),angular.module("journal.component.login",[]),angular.module("journal.component.markdownHelperModal",[]),angular.module("journal.component.header",[]),angular.module("journal.component.editor",["ngFileUpload","ngSanitize","ui.codemirror"]),angular.module("journal.component.installer",["ui.router"]),angular.module("journal.component.installerStart",["ui.router"]),angular.module("journal.component.installerDetails",["ui.router"]),angular.module("journal.component.installerSuccess",[]),angular.module("journal.component.postLists",["ui.router"]),angular.module("journal.component.services",[]),angular.module("journal.component.settings",["ui.bootstrap","ui.router"]),angular.module("journal.component.settingsModal",["ngFileUpload","ui.bootstrap","ui.router"]),angular.module("journal.component.sidebar",["ui.bootstrap","ui.router"]),angular.module("journal.component.userCreate",["ui.router"]),angular.module("journal.component.userLists",["angularMoment","ui.router"]),angular.module("journal.component.userProfile",["ui.bootstrap","ui.router"]),angular.module("journal.component.userProfileModal",["ngFileUpload","ui.bootstrap","ui.router"]),angular.module("journal.shared.auth",[]),angular.module("journal.shared.buttonLoader",[]),angular.module("journal.shared.fileUploader",["ngFileUpload"]),angular.module("journal.shared.toastr",["ngAnimate","toastr"]),angular.module("journal.shared.storage",["LocalStorageModule"]),angular.module("journal.shared.markdownConverter",["ngSanitize"])}(),function(){"use strict";function e(e,t,r){var n=""==r.CDN_URL?"/assets/templates":r.CDN_URL+"/assets/templates";t.otherwise("/").when("/","/post/lists").when("/post","/post/lists").when("/installer","/installer/start").when("/user","/user/lists"),e.state("editor",{url:"/editor",views:{"":{templateUrl:n+"/editor/editor.html"},header_content:{templateUrl:n+"/header/header.html"},sidebar_content:{templateUrl:n+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("landing",{url:"/",authenticate:!0,installer:!1}).state("login",{url:"/login",templateUrl:n+"/login/login.html",authenticate:!1,installer:!1}).state("installer",{url:"/installer",templateUrl:n+"/installer/installer.html",authenticate:!1,installer:!0,"abstract":!0}).state("installer.start",{url:"/start",views:{installer_content:{templateUrl:n+"/installer-start/installer-start.html"}}}).state("installer.details",{url:"/details",views:{installer_content:{templateUrl:n+"/installer-details/installer-details.html"}},authenticate:!1,installer:!0}).state("installer.success",{url:"/success",views:{installer_content:{templateUrl:n+"/installer-success/installer-success.html"}},authenticate:!1,installer:!0}).state("post",{url:"/post","abstract":!0,views:{"":{templateUrl:n+"/post/post.html"},header_content:{templateUrl:n+"/header/header.html"},sidebar_content:{templateUrl:n+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("post.lists",{url:"/lists",views:{post_content:{templateUrl:n+"/post-lists/post-lists.html"}},authenticate:!0,installer:!1}).state("postEditor",{url:"/editor/:postId",views:{"":{templateUrl:n+"/editor/editor.html"},header_content:{templateUrl:n+"/header/header.html"},sidebar_content:{templateUrl:n+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("services",{url:"/services",views:{"":{templateUrl:n+"/services/services.html"},header_content:{templateUrl:n+"/header/header.html"},sidebar_content:{templateUrl:n+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("settings",{url:"/settings",views:{"":{templateUrl:n+"/settings/settings.html"},header_content:{templateUrl:n+"/header/header.html"},sidebar_content:{templateUrl:n+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1}).state("user",{url:"/user",views:{"":{templateUrl:n+"/user/user.html"},header_content:{templateUrl:n+"/header/header.html"},sidebar_content:{templateUrl:n+"/sidebar/sidebar.html"}},authenticate:!0,installer:!1,"abstract":!0}).state("user.lists",{url:"/lists",views:{user_content:{templateUrl:n+"/user-lists/user-lists.html"}},authenticate:!0,installer:!1}).state("user.create",{url:"/create",views:{user_content:{templateUrl:n+"/user-create/user-create.html"}},authenticate:!0,installer:!1}).state("user.profile",{url:"/profile/:userId",views:{user_content:{templateUrl:n+"/user-profile/user-profile.html"}},authenticate:!0,installer:!1})}angular.module("journal.routes").config(["$stateProvider","$urlRouterProvider","CONFIG",e])}(),function(){"use strict";function e(e){angular.extend(e,{autoDismiss:!1,containerId:"toast-container",maxOpened:5,newestOnTop:!1,positionClass:"toast-top-right",preventDuplicates:!1,preventOpenDuplicates:!0,target:"body",timeOut:1e4})}function t(e){e.defaults.useXDomain=!0,delete e.defaults.headers.common["X-Requested-With"]}function r(e){e.setPrefix("journal")}angular.module("journal.config").config(["$httpProvider",t]).config(["toastrConfig",e]).config(["localStorageServiceProvider",r])}(),function(){"use strict";function e(e,t,r,n,s){e.$on("$stateChangeStart",function(e,r,o,a,i){s.start(),r.installer||!r.authenticate||n.user()||(t.transitionTo("login"),e.preventDefault()),r.name.indexOf("installer")>=0&&n.checkInstallation().success(function(r){return r.installed?(t.transitionTo("login"),void e.preventDefault()):void 0}),"login"==r.name&&n.user()&&n.getToken()&&(t.transitionTo("post.lists"),e.preventDefault())}),e.$on("$stateChangeSuccess",function(e,t,n,o,a){r(function(){s.done()})})}function t(e,t){return t.checkInstallation().success(function(r){return r.installed?void 0:(t.logout(),void e.transitionTo("installer.start"))}).error(function(r){t.logout(),e.transitionTo("installer.start")}),t.getToken()||t.user()?void(t.getToken()&&t.checkToken().success(function(e){e.user&&t.login(e.user)}).error(function(r){t.logout(),e.transitionTo("login")})):(t.logout(),void e.transitionTo("login"))}angular.module("journal.run").run(["$state","AuthService",t]).run(["$rootScope","$state","$timeout","AuthService","ngProgressLite",e])}(),function(){"use strict";angular.module("journal.constant").constant("CONFIG",{API_URL:"http://localhost:8000/api/v1.0",DEFAULT_AVATAR_URL:"http://40.media.tumblr.com/7d65a925636d6e3df94e2ebe30667c29/tumblr_nq1zg0MEn51qg6rkio1_500.jpg",DEFAULT_COVER_URL:"/assets/images/wallpaper.jpg",VERSION:"1.5.5",CDN_URL:"http://localhost:8000"})}(),function(){"use strict";function e(e,t,r,n,s){e.post=s,e.processing=!1,e.cancelPost=function(){t.dismiss("cancel")},e.deletePost=function(){e.processing=!0,r.deletePost(e.post.id).success(function(r){r.error||(n.toast('You have successfully deleted the post "'+e.post.title+'"',"success"),t.close({error:!1}))}).error(function(r){e.processing=!1,n.toast("Something went wrong. Please try again later.","error"),t.dismiss("cancel")})}}angular.module("journal.component.deletePostModal").controller("DeletePostModalController",["$scope","$modalInstance","DeletePostModalService","ToastrService","post",e])}(),function(){"use strict";function e(e,t,r,n,s,o){var a=this;a.sidebar=!1,a.post={author_id:n.user().id,status:2,tags:[]},a.editor={activeStatus:[],baseUrl:window.location.origin,codemirror:{mode:"markdown",tabMode:"indent",lineWrapping:!0},counter:0,status:[{"class":"danger",group:1,status:1,text:"Publish Now"},{"class":"primary",group:1,status:2,text:"Save as Draft"},{"class":"danger",group:2,status:2,text:"Unpublish Post"},{"class":"info",group:2,status:1,text:"Update Post"}],tags:[]},a.processing=!1,a.initialize=function(){a.editor.activeStatus=a.editor.status[1],r.postId&&s.getPost(r.postId).success(function(e){e.post&&(a.post=e.post,1==e.post.status&&(a.editor.activeStatus=a.editor.status[3]),a.post.published_at=a.convertTimestampToDate(e.post.published_at))}).error(function(e){}),r.postId||(a.post.published_at=a.convertTimestampToDate())},a.convertTimestampToDate=function(e){if(e){var t=new Date(1e3*e);return new Date(t.getFullYear(),t.getMonth(),t.getDate(),t.getHours(),t.getMinutes())}var r=new Date,n=new Date(r.getFullYear(),r.getMonth(),r.getDate(),r.getHours(),r.getMinutes());return n},a.deletePost=function(){var r=e.open({animation:!0,templateUrl:"/assets/templates/delete-post-modal/delete-post-modal.html",controller:"DeletePostModalController",resolve:{post:function(){return a.post}}});r.result.then(function(e){e.error||t.go("post.lists")})},a.savePost=function(){var e=a.post;a.processing=!0,s.save(e).success(function(e){var r=e.post;return a.processing=!1,a.post.id?(1==r.status&&(a.editor.activeStatus=a.editor.status[3]),2==r.status&&(a.editor.activeStatus=a.editor.status[1]),o.toast('You have successfully updated "'+r.title+'".',"success"),r.published_at=a.convertTimestampToDate(r.published_at),void(a.post=r)):(o.toast('You have successfully created the post "'+r.title+'".',"success"),void t.go("postEditor",{postId:r.id}))}).error(function(e){a.processing=!1})},a.setButtonClass=function(){var e="btn-default";switch(a.editor.activeStatus["class"]){case"danger":e="btn-danger";break;case"primary":e="btn-primary";break;case"info":e="btn-info"}return e+=a.processing?" processing":""},a.setPostStatus=function(e){a.editor.activeStatus=e,a.post.status=e.status},a.showMarkdownHelper=function(){e.open({animation:!0,templateUrl:"/assets/templates/markdown-helper-modal/markdown-helper-modal.html",controllerAs:"mhm",controller:"MarkdownHelperModalController",size:"markdown"})},a.showPane=function(e){a.editor.activePane=e},a.toggleSidebar=function(){a.sidebar=!a.sidebar},a.wordCounter=function(){return a.editor.counter>0?a.editor.counter+" words":"0 words"},a.initialize()}angular.module("journal.component.editor").controller("EditorController",["$modal","$state","$stateParams","AuthService","EditorService","ToastrService",e])}(),function(){"use strict";function e(e,t,r){var n=this;n.user=r.user(),n.logout=function(){r.logout(),t.go("login")},n.setActiveMenu=function(e){var r=t.current.name;return r.indexOf(e)>-1},n.toggleSidebar=function(){e.$broadcast("toggle-sidebar")}}angular.module("journal.component.header").controller("HeaderController",["$rootScope","$state","AuthService",e])}(),function(){"use strict";function e(e){var t=this;e.$on("installer-menu",function(e,r){t.active=r||1})}angular.module("journal.component.installer").controller("InstallerController",["$rootScope",e])}(),function(){"use strict";function e(e,t,r,n,s){e.$broadcast("installer-menu",2);var o=this;o.account=[],o.errors=[],o.processing=!1,o.createAccount=function(){o.processing=!0,s.createAccount(o.account).success(function(e){e.token&&(r.login(e.user),r.setToken(e.token),t.go("installer.success"))}).error(function(e){o.processing=!1,n.toast("There are some errors encountered.","error"),e.errors&&(o.errors=e.errors)})}}angular.module("journal.component.installerDetails").controller("InstallerDetailsController",["$rootScope","$state","AuthService","ToastrService","InstallerDetailsService",e])}(),function(){"use strict";function e(e,t,r,n){e.$broadcast("installer-menu",1);var s=this;s.processing=!1,s.install=function(){s.processing=!0,r.install().success(function(e){e.installed&&t.go("installer.details")}).error(function(){s.processing=!1,n.toast("Something went wrong.","error")})}}angular.module("journal.component.installerStart").controller("InstallerStartController",["$rootScope","$state","InstallerStartService","ToastrService",e])}(),function(){"use strict";function e(e,t,r,n){e.$broadcast("installer-menu",3);var s=this;s.initialize=function(){return r.user()||r.getToken()?void 0:(n.toast("Hey, something went wrong. Can you repeat again?","error"),void t.go("installer.start"))},s.go=function(){t.go("post.lists")},s.initialize()}angular.module("journal.component.installerSuccess").controller("InstallerSuccessController",["$rootScope","$state","AuthService","ToastrService",e])}(),function(){"use strict";function e(e,t,r,n){var s=this;s.loading=!1,s.login=[],s.authenticate=function(){var o=s.login;s.loading=!0,n.authenticate(o.email,o.password).success(function(n){return n.user&&n.token?(t.login(n.user),t.setToken(n.token),r.toast("Welcome, "+n.user.name,"success"),void e.go("post.lists")):void 0}).error(function(e){s.loading=!1;var t=e.errors.message;r.toast(t,"error")})}}angular.module("journal.component.login").controller("LoginController",["$state","AuthService","ToastrService","LoginService",e])}(),function(){"use strict";function e(e){var t=this;t.closeModal=function(){e.dismiss("cancel")}}angular.module("journal.component.markdownHelperModal").controller("MarkdownHelperModalController",["$modalInstance",e])}(),function(){"use strict";function e(e,t){var r=this;r.loading=!0,r.posts=[],r.activePost=null,r.deletePost=function(t){var n=e.open({animation:!0,templateUrl:"/assets/templates/delete-post-modal/delete-post-modal.html",controller:"DeletePostModalController",resolve:{post:function(){return t}}});n.result.then(function(e){if(!e.error){var n=r.posts.indexOf(t);r.posts.splice(n,1),r.activePost=r.posts[0],r.activePane="lists"}})},r.initialize=function(){t.getPosts().success(function(e){e.posts&&(r.posts=e.posts,r.activePost=e.posts[0]),r.loading=!1}).error(function(){r.loading=!1})},r.previewThisPost=function(e){r.activePost=e},r.initialize()}angular.module("journal.component.postLists").controller("PostListsController",["$modal","PostListService",e])}(),function(){"use strict";function e(e,t){var r=this;r.processing=!1,r.services=[],r.initialize=function(){e.getServices("google_analytics").success(function(e){e.settings&&(r.services=e.settings)}).error(function(){})},r.saveServices=function(){var n=r.services;r.processing=!0,e.saveServices(n).success(function(e){e.settings&&(r.processing=!1,t.toast("You have successfully updated your services.","success"))})},r.initialize()}angular.module("journal.component.services").controller("ServicesController",["ServicesService","ToastrService",e])}(),function(){"use strict";function e(e,t,r){var n=this;n.processing=!1,n.settings=[],n.themes=[],n.initialize=function(){r.getSettings("title,description,post_per_page,cover_url,logo_url,theme").success(function(e){e.settings&&(n.settings=e.settings)}),r.themes().success(function(e){e.themes&&(n.themes=e.themes)})},n.saveSettings=function(){n.processing=!0,r.saveSettings(n.settings).success(function(e){e.settings&&(n.processing=!1,t.toast("You have successfully updated the settings.","success"))})},n.selectedTheme=function(e){return n.settings.theme?n.settings.theme==e:!1},n.showImageUploader=function(t){var r=e.open({animation:!0,templateUrl:"/assets/templates/uploader-modal/uploader-modal.html",controller:"SettingsModalController",resolve:{settings:function(){return n.settings},type:function(){return t}}});r.result.then(function(e){n.settings=e})},n.initialize()}angular.module("journal.component.settings").controller("SettingsController",["$modal","ToastrService","SettingsService",e])}(),function(){"use strict";function e(e,t,r,n,s,o,a){e.activeOption="file",e.imageUrl=null,e.image={link:null,file:null},e.processing=!1,e.settings=o,e.upload={active:!1,percentage:0},e.$watch("image.file",function(){null!=e.image.file&&(e.processing=!0,s.upload(e.image.file).progress(function(t){e.upload={active:!0,percentage:parseInt(100*t.loaded/t.total)}}).success(function(t){t.url&&(e.processing=!1,e.imageUrl=t.url,e.upload={active:!1,percentage:0})}).error(function(){e.processing=!1,r.toast("Something went wrong with the upload. Please try again later.","error"),e.upload={active:!1,percentage:0}}))}),e.closeModal=function(){t.dismiss("cancel")},e.initialize=function(){"cover_url"==a&&e.settings.cover_url&&(e.imageUrl=e.settings.cover_url),"logo_url"==a&&e.settings.logo_url&&(e.imageUrl=e.settings.logo_url)},e.removeImage=function(){e.imageUrl=null,e.settings[a]=null},e.save=function(){e.processing=!0,e.settings[a]=e.imageUrl?e.imageUrl:e.image.link,n.saveSettings(e.settings).success(function(e){e.settings&&(r.toast("You have successfully updated the settings.","success"),t.close(e.settings))})},e.switchOption=function(){switch(e.activeOption){case"link":e.activeOption="file";break;case"file":e.activeOption="link";break;default:e.activeOption="file"}},e.initialize()}angular.module("journal.component.settingsModal").controller("SettingsModalController",["$scope","$modalInstance","ToastrService","SettingsService","FileUploaderService","settings","type",e])}(),function(){"use strict";function e(e,t,r,n){var s=this;s.openSidebar=!1,s.title="Journal",s.user=r.user(),t.$on("toggle-sidebar",function(){s.openSidebar=!s.openSidebar}),s.initialize=function(){n.getSettings("title").success(function(e){s.title=e.settings.title})},s.logout=function(){r.logout(),e.go("login")},s.tapOverlay=function(){s.toggleSidebar()},s.toggleSidebar=function(){s.openSidebar=!s.openSidebar},s.initialize()}angular.module("journal.component.sidebar").controller("SidebarController",["$state","$rootScope","AuthService","SidebarService",e])}(),function(){"use strict";function e(e,t){var r=this;r.user=[],r.errors=[],r.processing=!1,r.createUser=function(){r.errors=[],r.processing=!0,t.createUser(r.user).success(function(t){t.user&&(r.processing=!1,r.user=[],e.toast("You have successfully added "+t.user.name,"success"))}).error(function(t){if(t.errors){r.processing=!1,e.toast("There are errors encountered.","error"),r.errors=t.errors;for(var n in t.errors)e.toast(t.errors[n][0],"error")}})}}angular.module("journal.component.userCreate").controller("UserCreateController",["ToastrService","UserCreateService",e])}(),function(){"use strict";function e(e,t){var r=this;r.users=[],r.initialize=function(){e.getAllUsers().success(function(e){e.users&&(r.users=e.users)}).error(function(){})},r.setUserAvatarImage=function(e){return e.avatar_url?e.avatar_url:t.DEFAULT_AVATAR_URL},r.initialize()}angular.module("journal.component.userLists").controller("UserListsController",["UserListsService","CONFIG",e])}(),function(){"use strict";function e(e,t,r,n,s,o){var a=this;a.current=!1,a.user=[],a.password={},a.passwordErrors=[],a.processingChangePassword=!1,a.processingUpdateProfile=!1,a.initialize=function(){!t.userId,s.getUser(t.userId).success(function(e){e.user&&(a.current=r.user().id==e.user.id,a.user=e.user)}).error(function(e,t){})},a.setImage=function(e){var t=null;switch(e){case"cover_url":t=a.user.cover_url?a.user.cover_url:o.DEFAULT_COVER_URL,t="background-image: url('"+t+"')";break;case"avatar_url":t=a.user.avatar_url?a.user.avatar_url:o.DEFAULT_AVATAR_URL}return t},a.showImageUploader=function(t){if(a.current){var r=e.open({animation:!0,templateUrl:"/assets/templates/uploader-modal/uploader-modal.html",controller:"UserProfileModalController",resolve:{user:function(){return a.user},type:function(){return t}}});r.result.then(function(e){a.user=e})}},a.updatePassword=function(){var e=a.password;a.processingChangePassword=!0,s.updatePassword(e).success(function(e){e.user&&(a.processingChangePassword=!1,n.toast("You have successfully updated your password.","success"),a.password={})}).error(function(e){if(a.processingChangePassword=!1,n.toast("There are errors encountered.","error"),e.errors){if(e.message)return;for(var t in e.errors)n.toast(e.errors[t][0],"error")}})},a.updateProfile=function(){var e=a.user;a.processingUpdateProfile=!0,s.updateUserDetails(e).success(function(e){e.user&&(a.processingUpdateProfile=!1,n.toast("You have successfully updated your profile.","success"))}).error(function(e){a.processingUpdateProfile=!1})},a.initialize()}angular.module("journal.component.userProfile").controller("UserProfileController",["$modal","$stateParams","AuthService","ToastrService","UserProfileService","CONFIG",e])}(),function(){"use strict";function e(e,t,r,n,s,o,a){e.activeOption="file",e.imageUrl=null,e.image={link:null,file:null},e.processing=!1,e.user=o,e.upload={active:!1,percentage:0},e.$watch("image.file",function(){null!=e.image.file&&(e.processing=!0,s.upload(e.image.file).progress(function(t){e.upload={active:!0,percentage:parseInt(100*t.loaded/t.total)}}).success(function(t){t.url&&(e.processing=!1,e.imageUrl=t.url,e.upload={active:!1,percentage:0})}).error(function(){e.processing=!1,r.toast("Something went wrong with the upload. Please try again later.","error"),e.upload={active:!1,percentage:0}}))}),e.closeModal=function(){t.dismiss("cancel")},e.initialize=function(){"cover_url"==a&&e.user.cover_url&&(e.imageUrl=e.user.cover_url),"avatar_url"==a&&e.user.avatar_url&&(e.imageUrl=e.user.avatar_url)},e.removeImage=function(){e.imageUrl=null,e.settings[a]=null},e.save=function(){e.processing=!0,e.user[a]=e.imageUrl?e.imageUrl:e.image.link,n.updateUserDetails(e.user).success(function(e){e.user&&(r.toast("You have successfully updated your profile.","success"),t.close(e.user))}).error(function(t){e.processing=!1})},e.switchOption=function(){switch(e.activeOption){case"link":e.activeOption="file";break;case"file":e.activeOption="link";break;default:e.activeOption="file"}},e.initialize()}angular.module("journal.component.userProfileModal").controller("UserProfileModalController",["$scope","$modalInstance","ToastrService","UserProfileService","FileUploaderService","user","type",e])}(),function(){"use strict";function e(e,t){return{require:"ngModel",scope:{ngModel:"=",slug:"=",postId:"="},link:function(r,n,s,o){n.on("blur",function(){var n=o.$modelValue;n&&0!=n.length&&t.checkSlug(n,r.postId).success(function(t){t.slug&&e(function(){r.slug=t.slug})}).error(function(e){})})}}}function t(e){return{restrict:"E",templateUrl:"/assets/templates/editor/tags.html",scope:{postTags:"=tags"},link:function(t,r,n){t.inputtedTag="",t.journalTags=[],t.suggestedTags=[],t.tags=[],t.showSuggestions=!1,t.addTag=function(e){t.addToPostTag(e)},t.addToPostTag=function(e){for(var r in t.postTags)if(t.postTags[r].name==e)return;t.showSuggestions=!1,t.postTags.push({name:e}),t.inputtedTag="",t.setupSuggestionTags()},t.initialize=function(){e.getTags().success(function(e){e.tags&&(t.journalTags=e.tags,t.setupSuggestionTags())})},t.inputYourTag=function(e){t.showSuggestions=!0,13==e.which&&(e.preventDefault(),t.inputtedTag.length>0&&t.addToPostTag(t.inputtedTag)),t.inputtedTag.length<0&&(t.showSuggestions=!1)},t.setupSuggestionTags=function(){if(t.suggestedTags=t.journalTags,t.postTags)for(var e in t.postTags)for(var r in t.suggestedTags)t.postTags[e].name==t.suggestedTags[r].name&&t.suggestedTags.splice(r,1)},t.removePostTag=function(e){var r=t.postTags.indexOf(e);t.postTags.splice(r,1),t.suggestedTags.push(e)},t.initialize()}}}function r(){return{link:function(){angular.element(document.getElementsByClassName("CodeMirror-scroll")[0]).on("scroll",function(e){var t=angular.element(e.target),r=angular.element(document.getElementsByClassName("entry-preview-content")),n=angular.element(document.getElementsByClassName("CodeMirror-sizer")),s=angular.element(document.getElementsByClassName("rendered-markdown")),o=n[0].offsetHeight-t[0].offsetHeight,a=s[0].offsetHeight-r[0].offsetHeight,i=a/o,l=t[0].scrollTop*i;r[0].scrollTop=l})}}}function n(e,t){return{restrict:"EA",require:"ngModel",scope:{featuredImage:"=ngModel"},replace:!0,templateUrl:"/assets/templates/editor/featured-image.html",link:function(r,n,s,o){r.activeOption="file",r.imageUrl=null,r.image={link:null,file:null},r.processing=!1,r.upload={active:!1,percentage:0},r.getImageLink=function(){r.imageUrl=r.image.link,o.$setViewValue(r.imageUrl)},r.removeImage=function(){r.imageUrl=null,r.image.link=null,o.$setViewValue(r.imageUrl)},r.switchOption=function(){return"file"==r.activeOption?r.activeOption="link":"link"==r.activeOption?r.activeOption="file":void 0},r.$watch(function(){return r.image.file},function(n){n&&e.upload(n).progress(function(e){r.upload={active:!0,percentage:parseInt(100*e.loaded/e.total)}}).success(function(e){e.url&&(r.imageUrl=e.url,o.$setViewValue(r.imageUrl),r.upload={active:!1,percentage:0})}).error(function(){t.toast("Something went wrong with the upload. Please try again later.","error"),r.upload={active:!1,percentage:0}})}),r.$watch(function(){return o.$modelValue},function(e){r.imageUrl=e})}}}angular.module("journal.component.editor").directive("checkPostSlug",["$timeout","EditorService",e]).directive("tagInput",["EditorService",t]).directive("editorScroll",[r]).directive("featuredImage",["FileUploaderService","ToastrService",n])}(),function(){"use strict";function e(e){return{restrict:"EA",scope:{buttonLoader:"="},link:function(t,r,n){var s=function(){var t=r.text(),n=r[0].offsetWidth;e(function(){r.empty().css({width:n+"px"}).addClass("btn-loader").append("<p>"+t.toString()+"</p>").append('<i class="fa fa-cog fa-spin"></i>')})};n.buttonLoader&&t.$watch("buttonLoader",function(e){return e?r.addClass("btn-loading btn-disabled").attr("disabled","disabled"):r.removeClass("btn-loading btn-disabled").removeAttr("disabled")}),s()}}}angular.module("journal.shared.buttonLoader").directive("buttonLoader",["$timeout",e])}(),function(){"use strict";function e(e,t){return{restrict:"AE",scope:{journalMarkdown:"=",counter:"="},link:function(e,r,n){var s=function(){var t=0,n=r.text().replace(/^\s\s*/,"").replace(/\s\s*$/,"");n.length>0&&(t=n.match(/[^\s]+/g).length),e.counter=t};if(n.journalMarkdown)e.$watch("journalMarkdown",function(e){var o=e?t.makeHtml(e):"";n.hideScriptIframe&&(o=o.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,'<div class="embedded-javascript">Embedded JavaScript</div>'),o=o.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,'<div class="embedded-iframe">Embedded iFrame</div>')),r.html(o),s()});else{var o=t.makeHtml(r.text());n.hideScriptIframe&&(o=o.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,'<div class="embedded-javascript">Embedded JavaScript</div>'),o=o.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi,'<div class="embedded-iframe">Embedded iFrame</div>')),r.html(o),s()}}}}angular.module("journal.shared.markdownConverter").directive("journalMarkdown",["$sanitize","MarkdownConverter",e])}(),function(){"use strict";function e(){var e={};return{config:function(t){e=t},$get:function(){return new showdown.Converter(e)}}}angular.module("journal.shared.markdownConverter").provider("MarkdownConverter",[e])}(),function(){"use strict";function e(e,t,r){this.apiUrl=r.API_URL,this.deletePost=function(r){var n=t.getToken();return e.post(this.apiUrl+"/posts/delete?token="+n,{post_id:r})}}angular.module("journal.component.deletePostModal").service("DeletePostModalService",["$http","AuthService","CONFIG",e])}(),function(){"use strict";function e(e,t,r){this.apiUrl=r.API_URL,this.checkSlug=function(t,r){var n=this.apiUrl+"/posts/check_slug?slug="+(t||"");return r&&(n+="&post_id="+r),e.get(n)},this.getPost=function(t){return e.get(this.apiUrl+"/posts/get_post?post_id="+t)},this.getTags=function(){return e.get(this.apiUrl+"/tags/all")},this.save=function(r){var n=t.getToken(),s=this.apiUrl+"/posts/save?token="+n,o=r.author_id||"",a=r.title||"",i=r.markdown||"",l=r.featured_image||"",u=r.slug||"",c=r.status||2,d=r.tags||[],g=r.published_at.getTime()/1e3||Math.floor(Date.now()/1e3);return r.id&&(s+="&post_id="+r.id),e.post(s,{author_id:o,title:a,markdown:i,featured_image:l,slug:u,status:c,tags:d,published_at:g})}}angular.module("journal.component.editor").service("EditorService",["$http","AuthService","CONFIG",e])}(),function(){"use strict";function e(e,t){this.apiUrl=t.API_URL,this.createAccount=function(t){return e.post(this.apiUrl+"/installer/create_account",{email:t.email||"",name:t.name||"",password:t.password||"",title:t.title||""})}}angular.module("journal.component.installerDetails").service("InstallerDetailsService",["$http","CONFIG",e])}(),function(){"use strict";function e(e,t){this.apiUrl=t.API_URL,this.install=function(){return e.get(this.apiUrl+"/installer/install")}}angular.module("journal.component.installerStart").service("InstallerStartService",["$http","CONFIG",e])}(),function(){"use strict";function e(e,t){this.apiUrl=t.API_URL,this.authenticate=function(t,r){return e.post(this.apiUrl+"/auth/authenticate",{email:t||"",password:r||""})}}angular.module("journal.component.login").service("LoginService",["$http","CONFIG",e])}(),function(){"use strict";function e(e,t){this.apiUrl=t.API_URL,this.getPosts=function(){return e.get(this.apiUrl+"/posts/all")}}angular.module("journal.component.postLists").service("PostListService",["$http","CONFIG",e])}(),function(){"use strict";function e(e,t,r){this.apiUrl=r.API_URL,this.getServices=function(t){return e.get(this.apiUrl+"/settings/get?fields="+t)},this.saveServices=function(r){var n={},s=t.getToken();for(var o in r)n[o]=r[o];return e.post(this.apiUrl+"/settings/save?token="+s,n)}}angular.module("journal.component.services").service("ServicesService",["$http","AuthService","CONFIG",e])}(),function(){"use strict";function e(e,t,r){this.apiUrl=r.API_URL,this.getSettings=function(t){return e.get(this.apiUrl+"/settings/get?fields="+t)},this.saveSettings=function(r){var n={},s=t.getToken();for(var o in r)n[o]=r[o];return e.post(this.apiUrl+"/settings/save?token="+s,n)},this.themes=function(){return e.get(this.apiUrl+"/settings/themes")}}angular.module("journal.component.settings").service("SettingsService",["$http","AuthService","CONFIG",e])}(),function(){"use strict";function e(e,t){this.apiUrl=t.API_URL,this.getSettings=function(t){return e.get(this.apiUrl+"/settings/get?fields="+t)}}angular.module("journal.component.sidebar").service("SidebarService",["$http","CONFIG",e])}(),function(){"use strict";function e(e,t,r){this.apiUrl=r.API_URL,this.createUser=function(r){var n=t.getToken();return e.post(this.apiUrl+"/users/create?token="+n,{email:r.email||"",password:r.password||"",name:r.name||""})}}angular.module("journal.component.userCreate").service("UserCreateService",["$http","AuthService","CONFIG",e])}(),function(){"use strict";function e(e,t){this.apiUrl=t.API_URL,this.getAllUsers=function(){return e.get(this.apiUrl+"/users/all")}}angular.module("journal.component.userLists").service("UserListsService",["$http","CONFIG",e])}(),function(){"use strict";function e(e,t,r){this.apiUrl=r.API_URL,this.getUser=function(t){return e.get(this.apiUrl+"/users/get_user?user_id="+t)},this.updatePassword=function(r){var n=t.getToken(),s=t.user().id;return e.post(this.apiUrl+"/users/change_password?token="+n+"&user_id="+s,{old_password:r.old_password||"",new_password:r.new_password||"",repeat_password:r.repeat_password||""})},this.updateUserDetails=function(r){var n=t.getToken(),s=r.id||"";return e.post(this.apiUrl+"/users/update_details?token="+n+"&user_id="+s,{name:r.name||"",email:r.email||"",biography:r.biography||"",location:r.location||"",website:r.website||"",cover_url:r.cover_url||"",avatar_url:r.avatar_url||""})}}angular.module("journal.component.userProfile").service("UserProfileService",["$http","AuthService","CONFIG",e])}(),function(){"use strict";function e(e,t,r){
this.apiUrl=r.API_URL,this.checkInstallation=function(){return e.get(this.apiUrl+"/auth/check_installation")},this.checkToken=function(){return e.get(this.apiUrl+"/auth/check?token="+this.getToken())},this.getToken=function(){return t.get("token")},this.login=function(e){return t.set("user",JSON.stringify(e))},this.logout=function(){t.remove("user"),t.remove("token")},this.setToken=function(e){return t.set("token",e)},this.user=function(){return JSON.parse(t.get("user"))}}angular.module("journal.shared.auth").service("AuthService",["$http","StorageService","CONFIG",e])}(),function(){"use strict";function e(e,t,r){this.apiUrl=r.API_URL,this.upload=function(e){return t.upload({url:this.apiUrl+"/upload",file:e})}}angular.module("journal.shared.fileUploader").service("FileUploaderService",["AuthService","Upload","CONFIG",e])}(),function(){"use strict";function e(e){this.set=function(t,r){return e.set(t,r)},this.get=function(t){return e.get(t)},this.remove=function(t){return e.remove(t)}}angular.module("journal.shared.storage").service("StorageService",["localStorageService",e])}(),function(){"use strict";function e(e){this.toast=function(t,r){switch(r){case"success":e.success(t);break;case"info":e.info(t);break;case"error":e.error(t);break;case"warning":e.warning("message");break;default:e.success(t)}}}angular.module("journal.shared.toastr").service("ToastrService",["toastr",e])}();