// basic cookie handling
//////////////////////////////////////////////////////////////////////////////


/** create cookie
 * 
 * @param {string} name : cookie name
 * @param {*} value : cookie value
 * @param {int} expire : expiration time (in seconds)
 */
function createCookie(name, value, expire) {

    var expires;

    if (expire) {
        var date = new Date();
        date.setTime(date.getTime()+(expire * 1000));
        expires = "; expires=" + date.toGMTString();

    } else {
        expires = "";
    }

    // encode cookie
    let enc = encodeRFC3986URIComponent(value);

    document.cookie = name +"="+ enc + expires +"; path=/; SameSite=Strict";
}


/** read cookie
 * 
 * @param {string} name : cookie name
 * @returns cookie value
 */
function readCookie(name) {

    var nameEQ = name + "=";
    var ca = document.cookie.split(';');

    for(var i=0 ; i < ca.length ; i++ ) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return decodeURIComponent(c.substring(nameEQ.length,c.length));
        }
    }
    return null;
}

/** remove cookie
 * == create cookie with empty string, already expired
 */
function removeCookie(name) {
    createCookie(name, "", -1);
}


/** cookie exists
 * 
 * @param {string} name 
 * @returns {boolean} true|false
 */
function cookieExists(name) {

    var nameEQ = name + "=";
    var ca = document.cookie.split(';');

    for(var i=0 ; i < ca.length ; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1, c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return true;
        }
    }
    return false;
}


/** encodeRFC3986URIComponent
 * 
 * RFC3986 compatible URI encoding
 * 
 * @param str 
 * @returns (string) RCF3986 compatible encoded str
 */
function encodeRFC3986URIComponent(str) {
    return encodeURIComponent(str).replace(
      /[!'()*]/g,
      (c) => `%${c.charCodeAt(0).toString(16).toUpperCase()}`,
    );
}


/** example:
 * --- -- -- - - -
 * 
 * read connection cookie (as json object)
 * 
 * connection = JSON.parse(decodeURIComponent(readCookie('con')))
 * 
 * then...
 * connection = {
 *    rid: `role-id`,
 *    name: `full name` of connected user
 * }
 * 
 * so to refer to user's name ...
 * console.log(connection.name)
 * 
 */