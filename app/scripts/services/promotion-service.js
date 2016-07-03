App.Services.PromotionService = ['$http', function($http) {
        var self = this;
        // get all promotions
        this.getPromotions = function(onSuccess, onError) {
             $http.get('backend/data/promotions.json').
			    success(function(data, status, headers, config) {
			      onSuccess(data);
    			}).
			    error(function(data, status, headers, config) {
			      onError();
			    });
        };
        // opt in
        this.optIn = function(id, username, option, onSuccess, onError) {
            $http.get('backend/api.php?username='+username+'&action=optin&option='+option+'&promo='+id).
            success(function(data, status, headers, config) {
                  onSuccess(data);
                }).
                error(function(data, status, headers, config) {
                  onError(data.error);
                });
        }
        // get user details
        this.getUserDetails = function(username, onSuccess, onError) {
           $http.get('backend/api.php?username='+username+'&action=getUserDetails').
           success(function(data, status, headers, config) {
                  onSuccess(data);
                }).
                error(function(data, status, headers, config) {
                  onError();
                });
        }
        // get the cookie
        this.getCookie = function(onSuccess, onError) {
           $http.get('backend/api.php?action=getCookie').
           success(function(data, status, headers, config) {
                  onSuccess(data);
                }).
                error(function(data, status, headers, config) {
                  onError();
                });
        }
        // function that return the details of a promotion knowing the promotion id
        this.getPromotion = function(username, promotionId, onSuccess, onError) {
            $http.get('backend/api.php?action=getPromotion&username='+username+'&promo='+promotionId).
              success(function(data, status, headers, config) {
                  onSuccess(data);
                }).
                error(function(data, status, headers, config) {
                  onError();
                });
        }
        // remove cookie
        this.removeCookie =  function(onSuccess) {
          $http.get('backend/api.php?action=removeCookie').
              success(function(data, status, headers, config) {
                  onSuccess(data);
                }).
                error(function(data, status, headers, config) {
                  onError();
                });
        }
    }]