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
    updateStack.callbacks.push( updatePeelLog );
    window.requestAnimationFrame( updateInterval );
  }
  function updatePeelLog() {
    console.log( 'updating peel log' );
  }
  function initPeelLog( ts ) {
    $log = $( arguments[ 1 ] );
  }
  function updateInterval( ts ) {
    if( updateStack.ts == 0 ) {
      updateStack.ts = ts;
    }
    if( ( ts - updateStack.ts ) >= updateStack.ms ) {
      updateStack.ts = 0;
      for( var i = 0; i < updateStack.callbacks.length; i++ ) {
        console.log( typeof( updateStack.callbacks[ i ] ) );
        updateStack.callbacks[ i ]();
      }
    }
    window.requestAnimationFrame( updateInterval );
  }
  $( document ).ready( main );
} )( jQuery );