'use strict';

describe('Service: util', function () {

  // load the controller's module
  beforeEach(module('ngWitravelApp'));

  var util;

  // Initialize the controller and a mock scope
  beforeEach(inject(function (_util_) {
    util = _util_;
    // console.log(util);

  }));

  it('string2Num should convert any string to number', function () {
    var num = util.string2Num("123");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(123);

    num = util.string2Num("123abc");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(123);

    num = util.string2Num("xyz123abc");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(NaN);

    num = util.string2Num("xyz:123:abc");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(NaN);    
  });

  it('string2Num should convert any string to number', function () {
    var num = util.string2Num("123");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(123);

    num = util.string2Num("123abc");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(123);

    num = util.string2Num("xyz123abc");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(NaN);

    num = util.string2Num("xyz:123:abc");
    expect(typeof(num)).toEqual('number');
    expect(num).toEqual(NaN);    
  });  
});
