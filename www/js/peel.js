( function( $ ) {
  var
    updateStack = {
      ts: 0,
      ms: 1000,
      callbacks: [ ],
      addCallback: function( callback ) {
        this.callbacks.push( callback );
      }
    };
  function main() {
    $( '.peel_log' ).each( initPeelLog );
    window.requestAnimationFrame( updateInterval );
  }
  function updatePeelLog() {
    console.log( 'updating peel log' );
  }
  function initPeelLog( ts ) {
    $log = $( arguments[ 1 ] );
    updateStack.callbacks.push( updatePeelLog );
  }
  function updateInterval( ts ) {
    if( updateStack.ts == 0 ) {
      updateStack.ts = ts;
      if( ( ts - updateStack.ts ) >= updateStack.ms ) {
        updateStack.ts = 0;
        for( var i = 0; i < updateStack.callbacks.length; i++ ) {
          updateStack.callbacks[ i ]();
        } } } window.requestAnimationFrame( updateInterval );
  }
  $( document ).ready( main );
} )( jQuery );