/*Dalla documentazione di Jquery:
$( document ).ready()
A page can't be manipulated safely until the document is "ready." jQuery detects this state of readiness for you. Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute. Code included inside $( window ).on( "load", function() { ... }) will run once the entire page (images or iframes), not just the DOM, is ready.*/
$(document).ready(function() {
  var speed = 1000;
  //viene nascosto tutto l'html e il body
  $('html, body').hide();
  //viene mostrata animazione a velocità "speed"
  $('html, body').fadeIn(speed);
});