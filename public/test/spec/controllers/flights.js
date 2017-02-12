'use strict';

describe('Controller: FlightsCtrl', function () {

  // load the controller's module
  beforeEach(module('ngWitravelApp'));

  var MainCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    MainCtrl = $controller('FlightsCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  // it('should attach a list of awesomeThings to the scope', function () {
  //   expect(MainCtrl.awesomeThings.length).toBe(3);
  // });
});
