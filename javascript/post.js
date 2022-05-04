//funzione per mostrare il popup per creare un post
function OpenModalP() {
  //viene preso l'elemento associato all'id overlay-post,quello che contiene il form per creare un post e viene mostrato a schermo
  $( "#overlay-post" ).show( "slow");
}

//funzione per nascondere il popup per creare un post
function CloseModalP() {
    //viene preso l'elemento associato all'id overlay-post,quello che contiene il form per creare un post e viene nascosto dallo schermo
  $( "#overlay-post" ).hide( "slow");
}

//non si è usato style.visibility perchè l'elemento deve essere completamente rimosso dalla pagina quando è nascosto.