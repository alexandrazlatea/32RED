App.Services.LoginService = ['$http', 'configuration', function($http, configuration) {
   $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
  this.login = function(username,onLoginSuccess, onError) {
            $http.get('backend/api.php?username='+username+'&action=logIn').
            success(function(data, status, headers, config) {
                  onLoginSuccess(data);
                }).
                error(function(data, status, headers, config) {
                  onError(data.error);
                });
        }


 }]