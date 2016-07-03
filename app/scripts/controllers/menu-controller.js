'use strict';

App.Controllers.MenuController = ['$scope','$state', '$rootScope', 'PromotionService', function ($scope, $state, $rootScope, PromotionService) {
  $rootScope.$on('checkUser', function(event, profileObj) {
    var onSuccess = function (user) {
      if (user) {
        $scope.menuViewModel.logged = true;
      } else {
        $scope.menuViewModel.logged = false;
      }
    }
    PromotionService.getCookie(onSuccess); 
  });
   $scope.menuViewModel = new MenuViewModel($state,PromotionService);
}];

function MenuViewModel($state, PromotionService) {
  var self = this;
  this.logged = true;
  var onSuccess = function(user) {
    if (user) {
      self.logged = true;
    } else {
      self.logged = false;
    }
  }
  
  // if an error occured
  var onError = function() {
    self.logged = false;
  }
  function init() {
     PromotionService.getCookie(onSuccess, onError);
  }

  this.logOut = function() {
    PromotionService.removeCookie(onSuccess);
    $state.go('promotion');
  } 


  init();
}