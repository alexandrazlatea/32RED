'use strict';

App.Controllers.PromotionController = ['$scope', '$http', '$rootScope', 'PromotionService', '$stateParams', '$state', function($scope, $http, $rootScope, PromotionService, stateParams, $state) {
      $rootScope.$broadcast('checkUser');
        $scope.viewModel = new promotionViewModel(PromotionService, stateParams, $state);
    }];

function promotionViewModel(promotionService, stateParams, $state) {
    var self = this;
    var currentId = stateParams.id;

    self.isBusy = false;
    self.promotions = {};
    self.promotion = {};
    self.promotion.message = '';

    function init() {
        getPromotions();
    };
    
    function onSucces(result) {
        self.promotions = result;
      
    };

    function onError(error) {
        console.log(error);
    };
     
    function getPromotions(currentId) {
        promotionService.getPromotions( onSucces, onError);
    }
    this.truncateDescription = function(description) {
        return description.substr(0,200)+'...';
    } 

    this.goSinglePage =  function(pageId) {
       var params = {id: pageId};
       $state.go('promotion/1', params);
    }

   

    init();
}
