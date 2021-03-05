const isErrorWithResponseAndStatus = function(err){
    return err.response && err.response.status;
}


export const is404 = function(err){
    return isErrorWithResponseAndStatus(err) && err.response.status == 404;
}

export const is406 = function(err){
    return isErrorWithResponseAndStatus(err) && err.response.status == 406;
}

export const is422 = function(err){
    return isErrorWithResponseAndStatus(err) && err.response.status == 422;
}