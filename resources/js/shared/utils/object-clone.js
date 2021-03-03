export const deepPrimitiveOnly = function(value) {
    return JSON.parse(JSON.stringify(value));
}