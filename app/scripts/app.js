'use strict';

var app = angular.module('app', ['ui.router', 'ngResource']);

app.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
  $urlRouterProvider.otherwise('/promotion');

  $stateProvider.state('promotion', {
    url: '/promotion',
    templateUrl: 'app/views/promotion.html',
    controller: 'PromotionController'
  });
   $stateProvider.state('promotion/1', {
    url: '/promotion/:id',
    templateUrl: 'app/views/single-promotion.html',
    controller: 'SinglePromotionController'
  });
  $stateProvider.state('login', {
    url: '/login',
    templateUrl: 'app/views/log-in.html',
    controller: 'LoginController'
  });
   $stateProvider.state('logout', {
    url: '/logout',
    controller: 'LogoutController'
   });
  

}]);

app.controller(App.Controllers);
app.service(App.Services);
app.factory(App.Factories);
app.directive(App.Directives);

app.constant('configuration', {
  apiUrl: '/angular/public/'
});