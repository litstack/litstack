// Array
//
//

/**
 * Check if array equals another one.
 */
Array.equals = (a, b) => {
    if (a === b) return true;
    if (a == null || b == null) return false;

    for (let i in b) {
        if (!a.includes(b[i])) return false;
    }
    return true;
};

// String
//
//

String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.rawText = function() {
    return this.replace(/<[^>]*>?/gm, '').replace('&nbsp;', ' ');
};
