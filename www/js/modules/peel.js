define( [
  'jquery',
  'mustache',
  'text!../tpl/log_entry.html',
  'text!../tpl/ctrl_entry.html'
], function( $, mustache, tplLogEntry, tplCtrlEntry ) {

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

  function initialize() {
    updateStack.addCallback( updatePeelLog );
    updateStack.addCallback( updatePeelCtrl );
    window.requestAnimationFrame( updateInterval );
  }

  function populatePeelLst( status, lstElements, lstData, lstItemTemplate ) {
    if( status == 'success' ) {
      lstElements.each(
        function( i, e ) {
          $lst = $( e );
          $lst.html( '' );
          for( var k in lstData ) {
            $lst.append( mustache.render( lstItemTemplate, lstData[ k ] ) );
          }
        }
      );
    }
  }

  function updatePeelLog() {
    if( updatePeelLog.lock ) {
      populatePeelLst( arguments[ 1 ], $( '.peel_log' ), arguments[ 0 ], tplLogEntry );
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

  function updatePeelCtrl() {
    if( updatePeelCtrl.lock ) {
      populatePeelLst( arguments[ 1 ], $( '.peel_ctrl' ), arguments[ 0 ], tplCtrlEntry );
      updatePeelCtrl.lock = false;
    }
    else {
      updatePeelCtrl.lock = true;
      $.ajax( {
        url: '?json=peel_ctrl',
        success: updatePeelCtrl
      } );
    }
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

  return {
    'initialize': initialize
  }

} );