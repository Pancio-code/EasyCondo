# EasyCondo
progetto per il corso Linguaggi e tecnologie per il web

Il sito è pensato per permettere una comunicazione più facile tra i condomini e gli amministratori, fornendo le seguenti funzionalità:

1. Autenticazione utente: 
	-Login condomino/amministratore 
	-Registrazione condomino/amministratore 
	-Recupero password 
2. Gestione condominio da parte dell'amministratore/condomino: 
	- Creazione del forum condominio 
	- Eliminazione del forum condominio 
	- Uscita dal forum condominio 
	- Sistema di invito al forum condominio 
	- Visualizzazione membri del forum condominio 
	- elimina membri del forum condominio(amministratore) 
	- Vedere informazioni del gruppo
3. Gestione del profilo dell' utente: 
	- Visualizzazione dati account 
	- Modifica della password 
	- Modifica dell'immagine di profilo 
	- logout 
	- cancellazione account 
4. Post e commenti: 
	- Creazione di post con upload file all'interno del forum 
	- Possibilità di commentare i post 
	- Possibilità di cancellare i commenti 
	- Possibilità di visualizzare i commenti 
	- Eliminazione del post creato 
5. Messaggi amministratore:
	- Possibilità di messaggi privati tramite mail.
	- Possibilità di mandare messaggio tramite mail all' intero gruppo 
6. Form Contact Us 

Tecnologie utilizzate:
Per quanto riguarda il lato client sono stati utilizzati html & css insieme a javascript. Per rendere il sito responsive a qualsiasi formato e dispositivo ,si è scelto di usare Bootstrap (https://getbootstrap.com/), utilizzando i container,form,button e card spiegati nel dettaglio nella documentazione di Bootstrap. Per le varie icone presenti nel sito sono state utilizzate sia le icone di Bootstrap (https://icons.getbootstrap.com/),sia quelle di Fontawesome (https://fontawesome.com/v4/).Per quanto riguarda javascript è stato usato soprattutto per la creazione dinamica di elementi combinandolo con php.(https://jquery.com/) JQuery usato soprattutto per la validazione delle varie componenti delle form presenti nel sito per rendere la fruizione del sito migliore per il client.Inoltre JQuery è stato utilizzato per gestire i click dei post nei vari gruppi e insieme a (https://micku7zu.github.io/vanilla-tilt.js/) e (https://dimsemenov.com/plugins/magnific-popup/) sono state inserite delle animazioni in molti elementi del sito per rendere la fruizione lato client migliore.
Per quanto riguarda il lato server è stato utilizzato un database relazionale, MySQL, insieme a php, usato per inserire e prendere dati dal database ,ma anche per gestire richieste del client e per scambiare informazioni con le funzioni javascript.
Per la creazione della favicon è stato utilizzato il sito https://www.favicon-generator.org/.
Invece per quanto riguarda la gestione delle immagini profilo e dell' upload di file nei post si è scelto di salvare localmente il loro contenuto e non inserirli come tipo 'BLOB' all' interno del database.
Per quanto riguarda la sicurezza sono state usate funzioni di criptazione offerte direttamente da php per salvare le password nel database e anche per la condivisione dei link d'invito per i gruppi.Inoltre viene negato l'accesso a persone non loggate alle zone riservate del sito, evitando cosi azioni indesiderate.
Anche per il recupero password si è deciso di utilizzare codici criptati e un timer di mezzora, per evitare azioni indesiderate da chi riuscisse ad ottenere il link per cambiare la password non nel tempo stabilito.

Modalità d'uso:
1. http://easycondo.infinityfreeapp.com/?i=1

2. Per poter provare il sito localmente bisogna utilizzare XAMPP con i moduli apache e MySQL.Nell'istallazione scaricare anche php, fondamentale per il funzionamento del sito web.
La cartella SITE LTW deve essere inserita all'interno della cartella htdocs di xampp.
Dopodiché una volta avviato XAMPP con i sui moduli, digitare su qualsiasi browser web http://localhost/Site%20LTW/index.html, se sono state svolte correttamente l'operazioni precedenti dovrebbe comparire l' Homepage del sito. Invece per quanto riguarda il database digitare http://localhost/phpmyadmin/, qui creare un database con il nome 'serverdatabase',questi sono i parametri del file database.php:
    'db_engine' => 'mysql',
    'db_host' => '127.0.0.1',
    'db_name' => 'serverdatabase',
    'db_user' => 'root',
    'db_password' => ''.
Inoltre lanciare la query fornita 127_0_0_1.sql per ricreare il database sul proprio computer.
Inoltre molto importante è stata modificata la funzione mail di php per poter inviare email tramite il proprio account Gmail(https://www.youtube.com/watch?v=ZXdX5aFVMeo&t=45s) altrimenti si può configurare FileZilla.

Database:
Il database realizzato con mysql si articola in 6 tabelle:

-user(id,username,email,password_criptata,ruolo,foto_profilo)
Questa tabella ha il compito di imagazzinare i dati dell'utente inseriti al momento della registrazione, gli unici campi editabili all'interno del sito sono la password e la foto profilo.

-passord_change_request(id,code,username,time)
In questa tabella vengono inserite le richieste per il cambiamento della propria password in caso essa è stata smarrita,il campo codice criptato permette di creare link personali per cambiare la password.

-groups(id,username,codominio)
Questa tabella semplicemente raccoglie i condomini a cui sono iscritti i vari utenti.

-condominio(id,name,indirizzo,amministratore,citta,codice)
Questa tabella ha il compito di immagazzinare i dati dei condomini creati dagli amministratori, importante è il campo codice che permette l'invito di altri membri nel gruppo.

-message(id,username,title,condominio,message,dati)
Questa tabella raccoglie i vari post degli utenti, con i link degli allegati inseriti.

-comment(id,id_post,username,testo,dati)
Questa tabella raccoglie i commenti relativi ai post degli utenti.
		
Autori:
Andrea Panceri & Lorenzo Cirone (2022)
