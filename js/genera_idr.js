var txt_idr = document.getElementById('idr');
var txt_cognome = document.getElementById('cognome');
var txt_nome = document.getElementById('nome');
var cmd_genera_idr = document.getElementById('cmd_genera_idr');

/**
 * Genera un codice di regista a partire dal cognome e nome
 * @param {string} cognome 
 * @param {string} nome 
 */
function genera_idr(cognome, nome) {
    var idr = "";
    //Il tipo string supporta le seguenti funzioni che si possono concatenare per modificare la stringa:
    //.substr(inizio, lunghezza) estrae una sottostringa a partire dalla posizione inizio per la lunghezza.
    //.trim() rimuove eventuali spazi all'inizio e alla fine della stringa. Non rimuove gli spazi che si trovano a metà
    //.toLowerCase() converte i caratteri in minuscolo
    idr = cognome.substr(0, 3).trim().toLowerCase() + nome.substr(0, 3).trim().toLowerCase();

    while (idr.length < 6) {
        idr += "0";
    }
    return idr;
}

cmd_genera_idr.addEventListener('click', function () {
    txt_idr.value = genera_idr(txt_cognome.value, txt_nome.value);
});