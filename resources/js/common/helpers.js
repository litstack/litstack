window.ready = function (f) {
    /in/.test(document.readyState)
        ? setTimeout('window.ready(' + f + ')', 9)
        : f();
};

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

String.prototype.endsWith = function (suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

String.prototype.lowercaseFirst = function () {
    return this.charAt(0).toLowerCase() + this.slice(1);
};

String.prototype.capitalize = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.capitalizeAll = function (lower = false) {
    return (lower ? this.toLowerCase() : this).replace(
        /(?:^|\s|["'([{])+\S/g,
        (match) => match.toUpperCase()
    );
};

String.prototype.rawText = function () {
    return this.replace(/<[^>]*>?/gm, '').replace('&nbsp;', ' ');
};

String.prototype.hash = function () {
    var hash = 0,
        i,
        chr;
    for (i = 0; i < this.length; i++) {
        chr = this.charCodeAt(i);
        hash = (hash << 5) - hash + chr;
        hash |= 0; // Convert to 32bit integer
    }
    return hash;
};

String.prototype.slugify = function () {
    return this.replace(/[A-Z]/g, (m) => '-' + m.toLowerCase())
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');
};
