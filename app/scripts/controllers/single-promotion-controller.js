'use strict';

App.Controllers.SinglePromotionController = ['$scope', '$http', 'PromotionService', '$stateParams', '$state', function($scope, $http, PromotionService, stateParams, $state) {
        $scope.viewModel = new singlePromotionViewModel(PromotionService, stateParams, $state);
    }];

function singlePromotionViewModel(promotionService, stateParams, $state) {
    var self = this;
    self.promotion = {};
    // promotion id
    var promotionId = stateParams.id;
    self.showForm = true;

    var onSuccessGetPromotion =  function(data) {
        self.promotion = data.promotion;
        if (self.promotion != null) {
         if(self.promotion.hasOwnProperty('optionsList')){
            self.promotion.optionsList = self.promotion.optionsList.split(",");
            self.promotion.option = self.promotion.optionsList[0];
         }
        }
        if (data.status != 'eligible') {
           setMessage(data.status);
           setVisibility(true); 
        }
        // if the details about the promotion are not available
        if (data.promotion == null) {
            setMessage("We are sorry, but the promotion does not exist anymore");
            setVisibility(true); 
        }
        
    }
    var onError =  function(error) {
       setMessage(error);
       setVisibility(true); 
    }

    var getCookie = function() {
        promotionService.getCookie(onSuccesCookie, onError);
    } 

    var onSuccesCookie = function(username) {
        if (username != '') {
            self.username = username;
            self.showForm = false;
        }    
        promotionService.getPromotion(username, promotionId, onSuccessGetPromotion, onError);
    }
    
    // get user details
    function getUserDetails() {
       promotionService.getCookie(onSuccesCookie, onError);
    }

    // if the answer received from backend when the optin is pressed is not  
    var onSuccessOptin = function(data) {
       setMessage(data.status);
       setVisibility(true); 
    }
    

    // opt in to a promotion
    this.optIn = function(id) {
        var username = self.username;
        var option = self.promotion.option;
        promotionService.optIn(id, username, option, onSuccessOptin, onError);
    }

    // set visibility for the form 
    function setVisibility(value) {
         self.showPromotionErrorMessage = value;
    }
    
    // set the error message
    function setMessage(message) {
        self.message = message;
    }

    var init = function() {
        getCookie();
    };
    init();
}
