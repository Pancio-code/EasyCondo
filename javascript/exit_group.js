//funzione per mostrare il popup per eliminare un gruppo amministrato "name_group"
function OpenModalD(name_group) {
  //viene preso l'elemento associato all'id overlay-delete,quello che contiene la conferma dell'eliminazione del gruppo amministrato "name_group" e viene mostrato a schermo
  $( "#overlay-delete" ).show( "slow");
  //vengono presi tutti gli elementi con classe deletebtn,in particolare [0] perchè sappiamo che c'è un solo elemento nella pagina con quella classe.
  var intro = document.getElementsByClassName("deletebtn")[0];
  //viene messo come id di quell'elemento "name_group".
  intro.setAttribute('id', name_group);
}

//funzione per nascondere il popup per eliminare un gruppo amministrato
function CloseModalD() {
  //viene preso l'elemento associato all'id overlay-delete,quello che contiene la conferma dell'eliminazione del gruppo amministrato e viene nascosto dallo schermo
  $( "#overlay-delete" ).hide( "slow");
  //vengono presi tutti gli elementi con classe deletebtn,in particolare [0] perchè sappiamo che c'è un solo elemento nella pagina con quella classe.
  var intro = document.getElementsByClassName("deletebtn")[0];
  //viene messo come id di quell'elemento "".
  intro.setAttribute('id', "");
}

//funzione per mostrare il popup per uscire da uno dei propri gruppi "name_group"
function OpenModalX(name_group) {
  //viene preso l'elemento associato all'id overlay-exit,quello che contiene la conferma dell'uscita da uno dei propri gruppi "name_group" e viene mostrato a schermo
  $( "#overlay-exit" ).show( "slow");
  //vengono presi tutti gli elementi con classe exitbtn,in particolare [0] perchè sappiamo che c'è un solo elemento nella pagina con quella classe.
  var intro = document.getElementsByClassName("exitbtn")[0];
  //viene messo come id di quell'elemento "name_group".
  intro.setAttribute('id', name_group);
}

//funzione per nascondere il popup per uscire da uno dei propri gruppi
function CloseModalX() {
  //viene preso l'elemento associato all'id overlay-exit,quello che contiene la conferma dell'uscita da uno dei propri gruppi e viene nascosto dallo schermo
  $( "#overlay-exit" ).hide( "slow");
  //vengono presi tutti gli elementi con classe exitbtn,in particolare [0] perchè sappiamo che c'è un solo elemento nella pagina con quella classe.
  var intro = document.getElementsByClassName("exitbtn")[0];
  //viene messo come id di quell'elemento "name_group".
  intro.setAttribute('id', "");
}

//funzione che viene eseguita alla pressione dell'icon-button "Exit" della card del condominio
function exit_group(name_group)
{
  //viene inviata una chiamata get all'indirizzo scritto in window.location.href con valore il nome del gruppo "name_group".
  window.location.href = "http://localhost/Site%20LTW/php/exit_group.php?name_group=" + name_group;
  //Qui è stato usato un localhost,al momento della pubblicazione basterà mettere l'url del proprio sito e tutto funzionerà correttamente
}

//funzione che viene eseguita alla pressione dell'icon-button "Delete" della card del condominio
function delete_group(name_group)
{
  //viene inviata una chiamata get all'indirizzo scritto in window.location.href con valore il nome del gruppo "name_group".
  window.location.href = "http://localhost/Site%20LTW/php/delete_group.php?name_group=" + name_group;
  //Qui è stato usato un localhost,al momento della pubblicazione basterà mettere l'url del proprio sito e tutto funzionerà correttamente
}