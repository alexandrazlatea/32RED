'use strict';

App.Controllers.LoginController = ['$scope', '$state', '$rootScope','LoginService', function ($scope, $state, $rootScope, loginService) {
  $scope.viewModel = new LoginViewModel($state,loginService);
}];


function LoginViewModel($state, loginService) {
  var self = this;
  self.isBusy = false;
  self.username = "";
  self.showError = false;
  /**
     * Login user
     * 
     */
  this.loginUser = function () {
   self.isBusy = true;
   loginService.login(self.username, onLoginSuccess, onError);
  };

  // Login success call back
  function onLoginSuccess(user) {
    self.isBusy = false;
    $state.go('promotion');
  };

  // Login error call back
  function onError() {
    self.showError = true;
    self.isBusy = false;
     // to be implemented
  } 

}