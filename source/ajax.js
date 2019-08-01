/**
 * Mange AJAX connection and parametes.
 * @author Khaled Hassan
 * @category Connection
 * @link khaled.h.developer@gmail.com
 */
var AJAX = {
    /**
     * Private function use in call function to return HTTP object to use in AJAX connection.
     * @return obj HTTP object.
     */
    getHTTPObject: function () {
        var xhr;
        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        }
        return xhr;
    },
    /**
     * Call AJAX and make connection to send and receive data from server without reload web page.
     * @param URL string <br> The URL to server page want calling it. <p></p>
     * @param Type string <br> Sending type and have two value only (<b>'POST' or 'GET'</b>). <p></p>
     * @param parameter array <br> <b>Optional</b> <br> The associative array have parameter name and value. <p></p>
     * @param isJSON bool <br> <b>Optional</b> <br> <b>true</b> The return value from server is JSON array <br> <b>false</b> The return value from server or no return value. <p></p>
     * @param receiveFunction string <br> <b>Optional</b> <br> The function name call and send return value from server to it. <p></p>
     * @return Null <br> Call the receiveFunction if added.
     */
    call: function (URL, Type, parameter, isJSON, receiveFunction) {
        var request = this.getHTTPObject();
        Type = Type.toUpperCase();

        request.onreadystatechange = function () {
            /*check if the request is ready and that it was successful*/
            if (request.readyState === 4 && request.status === 200) {
                var requestData = request.responseText, receivedData = request.responseText, notice;

                /*check if have escape string*/
                var pos = requestData.indexOf("|*");
                if (pos > -1) {
                    requestData = requestData.substr(pos + 2);
                    try {
                        notice = receivedData.substr(0, pos);
                        send(notice);
                    } catch (err) {
                        /*Handle error(s) here*/
                    }
                }

                /*check if coming data in JSON format*/
                if (isJSON !== undefined && isJSON === true) {
                    /*convert request string to JSON object (JSON array)*/
                    requestData = JSON.parse(requestData);
                }

                /*check if send calling function with function to send data to it*/
                if (receiveFunction !== undefined) {
                    /*send request data to function send in receiveFunction parameter*/
                    window[receiveFunction](requestData);
                }
                return requestData;
            }
        };

        var params = '';
        /*check if send parameter to function or not*/
        if (parameter !== undefined) {
            /*loop in associative array to parameters send with AJAX connection*/
            for (var key in parameter) {
                params += key + '=' + parameter[key] + '&';
            }
            params = params.substring(0, params.length - 1);
        }
        
        if(Type === 'GET'){
            URL += '?' + params;
            params = '';
        }

        /*open new rquest to sever to send data and pass AJAX type and the url to send it*/
        request.open(Type, URL, true);

        /*check if connictio type is POST and if true send this in connection header*/
        if (Type === 'POST') {
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }

        /*send parameters with AJAX connction*/
        if (params === '') {
            request.send(null);
        } else {
            request.send(params);
        }
    },
    /**
     * Uploading form content without loading source.
     * @param form object <br> The form object. <p></p>
     * @return Null.
     */
    uploadimg: function (form) {
        event.preventDefault();

        form.submit();
        return false;
    },
    /**
     * Uploading form content without loading source (calling from server side page).
     * @param parent object <br> The parent object (HTML tag). <p></p>
     * @param file string <br> The new file name. <p></p>
     * @param callFunction string <br> The fnction name will call to processing data. <p></p>
     * @return Null.
     */
    doneLoading: function (parent, file, callFunction) {
        if (callFunction !== undefined) {
            parent.sendFile(file, callFunction);
        }
    }

};

/**
 * Send file data function.
 * @param file string <br> The new file name. <p></p>
 * @param callFunction string <br> The fnction name will call to processing data. <p></p>
 * @return Null.
 */
function sendFile(file, callFunction) {
    if (callFunction !== undefined) {
        window[callFunction](file);
    }
}

