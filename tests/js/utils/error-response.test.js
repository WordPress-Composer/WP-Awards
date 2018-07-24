import ErrorResponse, {
    APIResponseStructure,
    mapServerErrorResponse,
    ServerError
} from '../../../assets/js/utils/error-response';

describe('Error Response Util', function() {
    describe('Function: APIResponseStructure', function() {
        describe('When response is null', function() {
            test('It returns nothing', function() {
                let response = null;
                let result = APIResponseStructure(response);
                expect(result.isNothing()).toBe(true);
            });
        });

        describe('When response is not null but has no status', function() {
            test('It returns nothing', function() {
                let response = {
                    status: undefined
                }
                let result = APIResponseStructure(response);
                expect(result.isNothing()).toBe(true);
            });
        });

        describe('When response is not null but has no data', function() {
            test('It returns nothing', function() {
                let response = {}
                let result = APIResponseStructure(response);
                expect(result.isNothing()).toBe(true);
            });
        });

        describe('When response is not null, and has data but no error', function() {
            test('It returns nothing', function() {
                let response = {
                    data: null
                }
                let result = APIResponseStructure(response);
                expect(result.isNothing()).toBe(true);
            });
        });

        describe('When response is not null, has data and error, but no code and no message', function() {
            test('It returns nothing', function() {
                let response = {
                    data: {
                        error: null
                    }
                }
                let result = APIResponseStructure(response);
                expect(result.isNothing()).toBe(true);
            });
        });

        describe('When response is not null, has data, error and a message but no code', function() {
            test('It returns nothing', function() {
                let response = {
                    data: {
                        error: {
                            message: 'MESSAGE'
                        }
                    }
                }
                let result = APIResponseStructure(response);
                expect(result.isNothing()).toBe(true);
            });
        });
        
        describe('When response is not null, has data, error and code, but no message', function() {
            test('It returns nothing', function() {
                let response = {
                    data: {
                        error: {
                            code: 'CODE'
                        }
                    }
                }
                let result = APIResponseStructure(response);
                expect(result.isNothing()).toBe(true);
            });
        });

        describe('When response is not null, has status, data, error, code and message', function() {
            test('It returns just something', function() {
                let response = {
                    status: 200,
                    data: {
                        error: {
                            code: 'SOMETHING',
                            message: 'ELSE'
                        }
                    }
                }
                let result = APIResponseStructure(response);
                expect(result.isJust()).toBe(true);
                expect(result.just().status).toEqual(200);
                expect(result.just().data.error.code).toEqual('SOMETHING');
                expect(result.just().data.error.message).toEqual('ELSE');
            });
        });
    });

    describe('mapServerErrorResponse', function() {
        test('maps correct api structure', function() {
            let APIResponse = {
                status: 400,
                data: {
                    error: {
                        code: 'SOMETHING',
                        message: 'ELSE'
                    }
                }
            }
            let result = mapServerErrorResponse(APIResponse);
            expect(result.message).toEqual('ELSE');
            expect(result.code).toEqual('SOMETHING');
            expect(result.status).toEqual(400);
        })
    });

    describe('Default function', function() {
        test('throws API error Response if given standard structure', function() {
            expect(() => {
                let APIError = {
                    response: {
                        status: 400,
                        data: {
                            error: {
                                code: 'SOMETHING',
                                message: 'ELSE'
                            }
                        }
                    }
                }
                let result = ErrorResponse(APIError);
            }).toThrowError('ELSE');
        });

        test('throws standard error for random errors (assumed not to be from API)', function() {
            expect(() => {
                let result = ErrorResponse(APIError);
            }).toThrow('APIError is not defined')
        });
    })
});