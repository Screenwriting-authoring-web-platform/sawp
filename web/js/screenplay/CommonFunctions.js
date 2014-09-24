/**
 * Prints an error to the console if the jQuery node collection contains more than
 * one node.
 * @param {jQueryNode} jQueryNode The node collection that should contain only one node
 * @returns {undefined}
 */
function assertSinglejQueryNode(jQueryNode) {
    if (jQueryNode.length != 1)
        console.error(jst("js", 'Single node expected'));
}

/**
 * Returns a randomly generated color
 * @returns {String} the random color
 */
function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

/**
 * Compares two strings for lexicographical order
 * @param {String} a The first string
 * @param {String} b The second string
 * @returns {Number} if a < b returns -1. if a> b returns 1 and 0 otherwise.
 */
function compareStrings(a, b) {
    if (a < b)
        return -1;
    if (a > b)
        return 1;
    return 0;
}

/**
 * Truncates a string an extends it with "..." if its to long
 * @param {type} n
 * @returns {String.prototype|String}
 */
String.prototype.trunc = String.prototype.trunc ||
        function(n) {
            return this.length > n ? this.substr(0, n - 1) + '&hellip;' : this;
        };