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

  function onClickCtrl( e ) {
    $( e.target ).parent().addClass( 'pending' );
    $.ajax( { url: $( e.target ).attr( 'href' ), async: true } );
    e.preventDefault();
  }

  function populatePeelLst( status, lstElements, lstData, lstItemTemplate ) {
    if( status == 'success' ) {
      lstElements.each(
        function( i, e ) {
          $lst = $( e );
          for( var i = 0; i < lstData.length; i++ ) {
            var
              entry = lstData[ i ],
              $entry = $lst.find( '.entry_' + entry.key ).css( 'z-index', 0 )
              newEntries = [ ];
            if( $entry.length == 0 ) {
              newEntries.push( mustache.render( lstItemTemplate, entry ) );
            }
            else if( $entry.data( 'hash' ) != entry.hash ) {
              $lst
                .find( '.entry_' + entry.key )
                .replaceWith( mustache.render( lstItemTemplate, entry ) );
              $lst
                .find( '.entry_' + entry.key + ' .enabler' )
                .removeClass( 'pending' );
            }
            if( newEntries.length > 0 ) {
              $lst.prepend( newEntries )
            }
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
        success: updatePeelLog,
        cache: false
      } );
    }
  }

  function updatePeelCtrl() {
    if( updatePeelCtrl.lock ) {
      $( '.enabler' ).off( 'click', 'a', onClickCtrl );
      populatePeelLst( arguments[ 1 ], $( '.peel_ctrl' ), arguments[ 0 ], tplCtrlEntry );
      $( '.enabler' ).on( 'click', 'a', onClickCtrl );
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
      console.log( 'UPDATE' );
      updateStack.ts = ts;
      for( var i = 0; i < updateStack.callbacks.length; i++ ) {
        if( typeof( updateStack.callbacks[ i ] ) == 'function' ) {
          updateStack.callbacks[ i ]();
        }
      }
    }
    if( ( ts - updateStack.ts ) >= updateStack.ms ) {
      updateStack.ts = 0;
    }
    window.requestAnimationFrame( updateInterval );
  }

  return {
    'initialize': initialize
  }

} );