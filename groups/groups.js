//funzione chiamata quando l'utente vuole andare sulla bacheca del gruppo
function stream() {
  //viene nascosto momentaneamente il tooltip della bacheca
  $('.bacheca-b').tooltip('hide');
  let container_people = document.getElementById('people');
  let container_posts = document.getElementById('posts');
  //viene messo l'attributo display dell'elemento uguale a block per mostrarlo a schermo.
  container_posts.style.display = 'block';
  //viene messo l'attributo display dell'elemento uguale a none per nasconderlo dallo schermo.
  container_people.style.display = 'none';
  let button_post = document.getElementById('new-post');
  let button_memeber = document.getElementById('new-member');
  //viene messo l'attributo display dell'elemento uguale a block per mostrarlo a schermo.
  button_post.style.display = 'block';
  //viene messo l'attributo display dell'elemento uguale a none per nasconderlo dallo schermo.
  button_memeber.style.display = 'none';
}

function persone() {
  //tutti gli elementi che hanno data-toggle="tooltip" vengono legati al div btn-group ,così da non far fluttuare il toltip allo scroll della pagina
  $('[data-toggle="tooltip"]').tooltip({
    container: '.btn-group',
  });
  //viene nascosto momentaneamente il tooltip della sezione persone
  $('.persone-b').tooltip('hide');
  let container_people = document.getElementById('people');
  let container_posts = document.getElementById('posts');
  //viene messo l'attributo display dell'elemento uguale a none per nasconderlo dallo schermo.
  container_posts.style.display = 'none';
  //viene messo l'attributo display dell'elemento uguale a block per mostrarlo a schermo.
  container_people.style.display = 'block';
  let button_post = document.getElementById('new-post');
  let button_memeber = document.getElementById('new-member');
  //viene messo l'attributo display dell'elemento uguale a block per mostrarlo a schermo.
  button_memeber.style.display = 'block';
  //viene messo l'attributo display dell'elemento uguale a none per nasconderlo dallo schermo.
  button_post.style.display = 'none';
}

//funzione che gestisce creazione popup per eliminare un membro del gruppo amministrato e lo mostra
function OpenModalQ(name) {
  //viene preso elemento con id overlay-espelli e modificato il contenuto html al suo interno,questo popup contiene due bottoni per annullare o confermare l'espulsione di "name"
  document.getElementById("overlay-espelli").innerHTML = '<div class="popup" id="espelli-membro"><div onclick="CloseModalQ()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Espelli '+ name + '</h1><div class="underline"></div></div><p class="lead">Sei sicuro di voler espellere ' +name +' dal gruppo?</p><div class="clearfix"><button type="button" onclick="CloseModalQ()" class="cancelbtn btn">Annulla</button><button type="button" id="'+name+'" onclick="espelli(this.id);" class="exitbtn btn">Espelli</button></div></div>';
  //l'elemento con id overlay-espelli viene mostrato a schermo
  $( "#overlay-espelli" ).show( "slow")
}

//funzione che nasconde popup per eliminare un membro del gruppo amministrato
function CloseModalQ() {
  //l'elemento con id overlay-espelli viene nascosto dallo schermo
  $( "#overlay-espelli" ).hide( "slow");
}

//funzione che gestisce la chiusura del popup per eliminare un post o un commento al suo interno
function CloseModalC() {
  //se ci troviamo nella pagina group/group_np.php entriamo nell'if altrimenti in post.php entriamo nell'else
  if(document.getElementById("overlay-cancel-message") != null) {
    //l'elemento con id overlay-cancel-message viene nascosto dallo schermo
    $( "#overlay-cancel-message" ).hide( "slow");
  } else {
    //l'elemento con id overlay-cancel-comment viene nascosto dallo schermo
    $( "#overlay-cancel-comment" ).hide( "slow");
  }
}

//funzione nasconde popup per la conferma dell'eliminazione del post nella pagina post.php
function CloseModalCP() {
  //l'elemento con id overlay-cancel-post viene nascosto dallo schermo
  $( "#overlay-cancel-post" ).hide( "slow");
}

//funzione che crea e mostra popup per la conferma dell'eliminazione del post nella pagina post.php
function OpenModalCP(id) {
  document.getElementById("overlay-cancel-post").innerHTML = '<div class="popup" id="cancel-post"><div onclick="CloseModalCP()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Cancellazione post</h1><div class="underline"></div></div><p class="lead">Sei sicuro di voler cancellare il tuo post?</p><div class="clearfix"><button type="button" onclick="CloseModalC()" class="cancelbtn btn">Annulla</button><button type="button" id="'+ id +'" onclick="cancel(' + id + ');" class="exitbtn btn">Cancella</button></div></div>';
  //l'elemento con id overlay-cancel-post viene mostrato a schermo
  $( "#overlay-cancel-post" ).show( "slow");
}

//funzione che gestisce l'apertura e la creazione dei popup per eliminare un post o un commento al suo interno
function OpenModalC(id) {
  //se ci troviamo nella pagina group/group_np.php entriamo nell'if altrimenti in post.php entriamo nell'else.
  //il parametro "id" verrà passato alla funzione cancel o cancel_comm ,che si occupano alla pressione del bottone "Cancella" di eliminare effettivamente il messaggio/commento
  if(document.getElementById("overlay-cancel-message") != null) {
    document.getElementById("overlay-cancel-message").innerHTML = '<div class="popup" id="cancel-post"><div onclick="CloseModalC()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Cancellazione post</h1><div class="underline"></div></div><p class="lead">Sei sicuro di voler cancellare il tuo post?</p><div class="clearfix"><button type="button" onclick="CloseModalC()" class="cancelbtn btn">Annulla</button><button type="button" id="'+ id +'" onclick="cancel(' + id + ');" class="exitbtn btn">Cancella</button></div></div>';
    //l'elemento con id overlay-cancel-message viene mostrato a schermo
    $( "#overlay-cancel-message" ).show( "slow");
  } else {
    document.getElementById("overlay-cancel-comment").innerHTML = '<div class="popup" id="cancel-comment"><div onclick="CloseModalC()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Cancellazione commento</h1><div class="underline"></div></div><p class="lead">Sei sicuro di voler cancellare il tuo commento?</p><div class="clearfix"><button type="button" onclick="CloseModalC()" class="cancelbtn btn">Annulla</button><button type="button" id="'+ id +'" onclick="cancel_comm(\'' + id + '\');" class="exitbtn btn">Cancella</button></div></div>';
    //l'elemento con id overlay-cancel-comment viene mostrato a schermo
    $( "#overlay-cancel-comment" ).show( "slow");
  }
}

//funzione che viene eseguita alla pressione dell'icon-button "Delete" sulla card del post.
function cancel(id)
{
  //viene inviata una chiamata get all'indirizzo scritto in window.location.href con valore l'id del post da cancellare
  window.location.href = "http://localhost/Site%20LTW/groups/delete_message.php?id=" + id;
}

//funzione che viene eseguita alla pressione dell'icon-button "Delete" sulla card del commento.
function cancel_comm(ids)
{
  //il parametro passato sarà una stringa composta da due parole: id_commento + " " + id_post
  const myArray = ids.split(" ");
  //viene inviata una chiamata get all'indirizzo scritto in window.location.href con due valori:l'id del commento e l'id del post
  window.location.href = "http://localhost/Site%20LTW/groups/commenti/delete_comment.php?id=" + myArray[0]+ "&id_post=" + myArray[1];
} 

//funzione che viene eseguita alla pressione dell' icon-button "Expell" da parte dell'amministratore
function espelli(name)
{
  //viene inviata una chiamata get all'indirizzo scritto in window.location.href con valore il nome del membro da espellere
  window.location.href = "http://localhost/Site%20LTW/groups/espelli.php?name=" + name;
}

//funzione che viene eseguita alla pressione del bottone "Commenti" della card del post
function enter_post(id)
{
  //viene inviata una chiamata get all'indirizzo scritto in window.location.href con il valore id del post cliccato
  window.location.href = "http://localhost/Site%20LTW/groups/select_post.php?id=" + id;
}

//Qui è stato usato un localhost,al momento della pubblicazione basterà mettere l'url del proprio sito e tutto funzionerà correttamente

//non si è usato style.visibility perchè l'elemento deve essere completamente rimosso dalla pagina quando è nascosto.