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
    startUpdateInterval();
  }
  function updatePeelLog() {
    console.log( 'updateing peel log' );
  }
  function initPeelLog( ts ) {
    $log = $( arguments[ 1 ] );
    updateStack.addCallback( updatePeelLog );
  }
  function startUpdateInterval() {
    window.requestAnimationFrame(
      function( ts ) {
        if( updateStack.ts == 0 ) {
          updateStack.ts = ts;
          if( ( ts - updateStack.ts ) >= updateStack.ms ) {
            updateStack.ts = 0;
            for( var i = 0; i < updateStack.callbacks.length; i++ ) {
              if( typeof( updateStack.callbacks[ i ] ) == 'Function' ) {
                updateStack.callbacks[ i ]();
              } } } } window.requestAnimationFrame( startUpdateInterval );
      } );
  }
  $( document ).ready( main );
} )( jQuery );