( function( $ ) {
  function main() {
    $( '.peel_log' ).each( init_peel_log )
  }
  function init_peel_log( $log ) {
    console.log( $log );
  }
  $( document ).ready( main );
} )( jQuery );