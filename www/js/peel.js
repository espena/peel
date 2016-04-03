( function( $ ) {
  var
    updateStack = {
      ts: 0,
      ms: 2000,
      callbacks: [ ],
      addCallback: function( callback ) {
        this.callbacks.push( callback );
        callback.lock = false;
      }
    };
  function main() {
    $( '.peel_log' ).each( initPeelLog );
    updateStack.addCallback( updatePeelLog );
    window.requestAnimationFrame( updateInterval );
  }
  function updatePeelLog() {
    if( updatePeelLog.lock ) {
      if( arguments[ 1 ] == 'success' ) {
        var logData = arguments[ 0 ];
        console.log( logData );
        $( '.peel_log' ).each(
          function( i, e ) {
            $log = $( e );
            $log.html( '' );
            for( var k in logData ) {
              $log.append( '<p>' + logData[ k ].entry + '</p>' );
            }
          }
        );
      }
      updatePeelLog.lock = false;
    }
    else {
      updatePeelLog.lock = true;
      $.ajax( {
        url: '?json=peel_log',
        success: updatePeelLog
      } );
    }
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
        if( typeof( updateStack.callbacks[ i ] ) == 'function' ) {
          updateStack.callbacks[ i ]();
        }
      }
    }
    window.requestAnimationFrame( updateInterval );
  }
  $( document ).ready( main );
} )( jQuery );