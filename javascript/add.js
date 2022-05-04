//funzione per mostrare il popup per creare un condominio
function OpenModal() {
  //viene preso l'elemento associato all'id overlay,quello che contiene il form per creare un condominio e viene mostrato a schermo
  $( "#overlay" ).show( "slow");
}

//funzione per nascondere il popup per creare un condominio
function CloseModal() {
  //viene preso l'elemento associato all'id overlay,quello che contiene il form per creare un condominio e viene nascosto dallo schermo
  $( "#overlay" ).hide( "slow");
}

//non si è usato style.visibility perchè l'elemento deve essere completamente rimosso dalla pagina quando è nascosto.
