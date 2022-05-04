//funzione per mostrare il popup per eliminare il proprio account
function OpenModalD() {
  //viene preso l'elemento associato all'id overlay-delete,quello che contiene la conferma dell'eliminazione del prorprio account e viene mostrato a schermo
  $( "#overlay-delete" ).show( "slow");
}

//funzione per nascondere il popup per creare un condominio
function CloseModalD() {
  //viene preso l'elemento associato all'id overlay-delete,quello che contiene la conferma dell'eliminazione del prorprio account  e viene nascosto dallo schermo
  $( "#overlay-delete" ).hide( "slow");
}

//funzione per mostrare il popup per effettuare il logout
function OpenModalX() {
  //viene preso l'elemento associato all'id overlay-exit,quello che contiene la conferma del logout dal proprio account e viene mostrato a schermo
  $( "#overlay-exit" ).show( "slow");
}

//funzione per nascondere il popup per effettuare il logout
function CloseModalX() {
  //viene preso l'elemento associato all'id overlay-exit,quello che contiene la conferma del logout dal proprio account e viene nascosto dallo schermo
  $( "#overlay-exit" ).hide( "slow");
}

//funzione per mostrare il popup per cambiare la propria password
function OpenModalP() {
  //viene preso l'elemento associato all'id overlay-password,quello che contiene il form per inserire la nuova password e viene mostrato a schermo
  $( "#overlay-password" ).show( "slow");
}

//funzione per nascondere il popup per cambiare la propria password
function CloseModalP() {
  //viene preso l'elemento associato all'id overlay-password,quello che contiene il form per inserire la nuova password e viene nascosto dallo schermo.
  $( "#overlay-password" ).hide( "slow");
}

//funzione per mostrare il popup per cambiare l'immagine di profilo
function OpenModalF() {
  //viene preso l'elemento associato all'id overlay-foto,quello che contiene il form per inserire la nuova immagine di profilo e viene mostrato a schermo
  $( "#overlay-foto" ).show( "slow");
}

//funzione per nascondere il popup per cambiare l'immagine di profilo
function CloseModalF() {
  //viene preso l'elemento associato all'id overlay-foto,quello che contiene il form per inserire la nuova immagine di profilo e viene nascosto dallo schermo.
  $( "#overlay-foto" ).hide( "slow");
}

//non si è usato style.visibility perchè l'elemento deve essere completamente rimosso dalla pagina quando è nascosto.

