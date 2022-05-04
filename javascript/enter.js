//funzione per mostrate il popup per entrare in un condominio
function OpenModalE() {
  //viene preso l'elemento associato all'id overlay-enter,quello che contiene il form per creare un condominio e viene mostrato a schermo
  $( "#overlay-enter" ).show( "slow");
}

//funzione per nascondere il popup per entrare in un condominio
function CloseModalE() {
  //viene preso l'elemento associato all'id overlay-enter,quello che contione il form per creare un condominio e viene nascosto dallo schermo
  $( "#overlay-enter" ).hide( "slow");
}

//funzione che viene eseguita alla pressione del bottone "Entra" del card del condominio
function enter_group(name)
{
  //il parametro passato sarà una stringa composta da due parole: nome_gruppo + " " + codice_gruppo
  const myArray = name.split(" ");
  //viene inviata una chiamata get all'indirizzo scritto in window.location.href con due valori:il nome del gruppo e il codice del gruppo
  window.location.href = "http://localhost/Site%20LTW/php/select_group.php?name=" + myArray[0] + "&code=" + myArray[1];
  //Qui è stato usato un localhost,al momento della pubblicazione basterà mettere l'url del proprio sito e tutto funzionerà correttamente
}

//funzione per mostrate il popup per mandare un messaggio a un condomino "nome".La funzione viene incovacata al click del bottone visibile solo all'amministratore nella pagina del gruppo amministrato.
function OpenModalMss(nome) {
  //viene preso l'elemento associato all'id overlay-send-message,quello che contiene il form per inserire un messaggio per uno specifico condomino "nome". Con "innerHTML" viene modificato il codice html contenuto nel tag
  document.getElementById("overlay-send-message").innerHTML = '<div class="popup"><div onclick="CloseModalMss()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Messaggio per '+nome+'</h1><div class="underline"></div></div><section class="condominio"><div class="form"><form id="formmss" action="groups/message.php" method="post" enctype="multipart/form-data"><div class="form-group"><label for="messaggio"><h2>Messaggio:</h2></label><textarea name="messaggio" class="form-control form-control-lg" id="messaggio" placeholder="inserisci messaggio" rows="3" minlength="3" maxlength="255" required></textarea></div><input type="hidden" id="' + nome + '"  name="nome" value="' + nome + '" /><button id="submit" type="submit" class="submit-btn btn  btn-lg" name="send">Invia</button></form></div></section></div>';
  //viene preso l'elemento associato all'id overlay-send-message e viene mostrato a schermo
  $( "#overlay-send-message" ).show( "slow");
}

//funzione per nascondere il popup per mandare un messaggio a un solo condomino.
function CloseModalMss() {
  //viene preso l'elemento associato all'id overlay-send-message e viene nascosto dallo schermo
  $( "#overlay-send-message" ).hide( "slow");
}

//funzione per mostrate il popup per mandare un messaggio a tutti i condomini.La funzione viene incovacata al click del bottone visibile solo all'amministratore nella pagina del gruppo amministrato.
function OpenModalAll() {
  //viene preso l'elemento associato all'id overlay-send-message-all,quello che contiene il form per inserire un messaggio per tutti i condomini. Con "innerHTML" viene modificato il codice html contenuto nel tag
  document.getElementById("overlay-send-message-all").innerHTML = '<div class="popup"><div onclick="CloseModalAll()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Avviso per tutti i condomini</h1><div class="underline"></div></div><section class="condominio"><div class="form"><form id="formmss" action="groups/message_all.php" method="post" enctype="multipart/form-data"><div class="form-group"><label for="messaggio"><h2>Messaggio:</h2></label><textarea name="messaggio" class="form-control form-control-lg" id="messaggio" placeholder="inserisci messaggio" rows="3" minlength="3" maxlength="255" required></textarea></div><button id="submit" type="submit" class="submit-btn btn  btn-lg" name="send-all">Invia</button></form></div></section></div>';
  //viene preso l'elemento associato all'id overlay-send-message-all e viene mostrato a schermo
  $( "#overlay-send-message-all" ).show( "slow");
}

//funzione per nascondere il popup per mandare un messaggio a tutti i condomini.
function CloseModalAll() {
  //viene preso l'elemento associato all'id overlay-send-message-all e viene nascosto dallo schermo
  $( "#overlay-send-message-all" ).hide( "slow");
}

//non si è usato style.visibility perchè l'elemento deve essere completamente rimosso dalla pagina quando è nascosto.