String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.rawText = function() {
    return this.replace(/<[^>]*>?/gm, '').replace('&nbsp;', ' ');
};
