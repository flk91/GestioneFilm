var txt_idr = document.getElementById('idr');
var txt_cognome = document.getElementById('cognome');
var txt_nome = document.getElementById('nome');
var cmd_genera_idr = document.getElementById('cmd_genera_idr');

/**
 * 
 * @param {string} cognome 
 * @param {string} nome 
 */
function genera_idr(cognome, nome) {
    var idr = "";
    idr = cognome.substr(0, 3) + nome.substr(0, 3);
    while (idr.length < 6) {
        idr += "0";
    }
    return idr;
}

cmd_genera_idr.addEventListener('click', function () {
    txt_idr.value = genera_idr(txt_cognome.value, txt_nome.value);
});