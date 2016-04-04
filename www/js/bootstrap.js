requirejs.config( {
  'baseUrl': 'js',
  'paths': {
    'jquery': 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.min',
    'mustache': 'https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.2.1/mustache.min',
    'text': 'https://cdnjs.cloudflare.com/ajax/libs/require-text/2.0.12/text.min'
  }
} );
require( [ 'modules/peel' ], function( peel ) {
  peel.initialize();
} );