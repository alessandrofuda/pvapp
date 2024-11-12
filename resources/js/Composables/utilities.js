// utilities js functions (ex mixins.js) - MORE functions in this files
import moment from "moment";

export function truncateString(str, maxLength = 100, ending = ' .....') {
    if (str?.length > maxLength) {
        return str.slice(0, maxLength) + ending;
    }
    return str;
}

export function dateFormatter(dateString, format = 'YYYY-MM-DD HH:mm:ss') {
    if(!dateString) return '';
    return moment(dateString).format(format);
}

// export function exampleFunc2(param) {
//     return param;
// }

