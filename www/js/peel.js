( function( $ ) {
  function main() {
    $( '.peel_log' ).each( init_peel_log )
  }
  function init_peel_log() {
    $log = arguments[ 2 ];
    console.log( arguments );
  }
  $( document ).ready( main );
} )( jQuery );