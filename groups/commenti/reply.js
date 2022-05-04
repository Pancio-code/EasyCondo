function CloseModalR() {
  $( "#overlay-reply-message" ).hide( "slow");
}

function OpenModalR(id,user) {
  document.getElementById("overlay-reply-message").innerHTML = '<div class="popup" id="rispondi-post"><div onclick="CloseModalR()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Rispondi a '+ user+'</h1><div class="underline"></div></div><section class="condominio"><div class="form"><form id="form-r" action="groups/commenti/commenta.php" method="post" enctype="multipart/form-data"><div class="form-group"><label for="ReplyLabel"><h2>Risposta:</h2></label><textarea name="reply" class="form-control form-control-lg" id="ReplyLabel" placeholder="inserisci risposta" rows="3" minlength="3"></textarea></div><div class="form-group"><label for="customFile"><h2>Eventuali Allegati</h2></label><input type="file" name="file[]" class="form-control" id="files" multiple /></div><input type="hidden" id="'+ id +'"  name="id_post" value="'+ id +'" /><button type="submit" class="submit-btn btn btn-info btn-lg reply" name="post-reply">Rispondi</button></form></div></section></div></div>';
  $( "#overlay-reply-message" ).show( "slow");
}

function OpenModalRP(id,user) {
  document.getElementById("overlay-reply-message").innerHTML = '<div class="popup" id="rispondi-post"><div onclick="CloseModalR()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div><div class="section-header"><h1 class="display-1 section-heading">Rispondi a '+ user+'</h1><div class="underline"></div></div><section class="condominio"><div class="form"><form id="form-r" action="commenta.php" method="post" enctype="multipart/form-data"><div class="form-group"><label for="ReplyLabel"><h2>Risposta:</h2></label><textarea name="reply" class="form-control form-control-lg" id="ReplyLabel" placeholder="inserisci risposta" rows="3" minlength="3"></textarea></div><div class="form-group"><label for="customFile"><h2>Eventuali Allegati</h2></label><input type="file" name="file[]" class="form-control" id="files" multiple /></div><input type="hidden" id="'+ id +'"  name="id_post" value="'+ id +'" /><button type="submit" class="submit-btn btn btn-info btn-lg reply" name="post-reply">Rispondi</button></form></div></section></div></div>';
  $( "#overlay-reply-message" ).show( "slow");
}

//funzione che nella pagina post.php si occupa di espandere il form per rispondere al post
function open_reply() {
  //viene nascosto il bottone di dropdown,invece vengono mostrati il form della risposta e il pulsante per nascondere il pannello espanso
  let expands = document.getElementById('expand-button');
  //viene messo l'attributo display dell'elemento uguale a none per nasconderlo dallo schermo.
  expands.style.display = 'none';
  $( "#expanded_comment" ).slideDown("slow");
  $( "#nonexpand_button" ).slideDown("slow");
}

//funzione che nella pagina post.php si occupa di richiudere il form per rispondere al post
function close_reply() {
  //viene nascosto il form per rispondere e il pulsante per nascondere il pannello espanso,viene rimostrato il pulsante di dropdown
  let expands = document.getElementById('expand-button');
  //viene messo l'attributo display dell'elemento uguale a block per mostrarlo a schermo.
  expands.style.display = 'block';
  $( "#expanded_comment" ).slideUp( "slow");
  $( "#nonexpand_button" ).slideUp( "slow");
}

//non si è usato style.visibility perchè l'elemento deve essere completamente rimosso dalla pagina quando è nascosto.