<?php

  define( 'TEMPLATE_TAG_PATTERN', '/%%([^%]+)%%/' );
  define( 'TEMPLATE_FUNCTION_PATTERN', '/^([^\(]+)\([^\)]*)\)$/' );

  class Template {

    private $mTplText;

    public function __construct( $idt ) {
      $tplFile = sprintf( '%s/%s.tpl', DIR_TPL, $idt );
      $this->mTplText = file_exists( $tplFile ) ? file_get_contents( $tplFile ) : '';
    }

    public function render( $data ) {
      $resolved = array();
      if( preg_match_all( TEMPLATE_TAG_PATTERN, $this->mTplText, $m, PREG_SET_ORDER ) ) {
        foreach( $m as $tag ) {
          $subject = $tag[ 0 ];
          if( !isset( $resolved[ $subject ] ) ) {
            $expression = trim( $tag[ 1 ] );
            $a = explode( '|', $expression );
            $n = count( $a );
            $field = trim( $a[ 0 ] );
            $functions = array();
            for( $i = 1; $i < $n - 1; $i++ ) {
              if( preg_match( TEMPLATE_FUNCTION_PATTERN, trim( $a[ $i ] ), $m ) ) {
                $functions[ trim( $m[ 1 ] ) ] = array_map( 'trim', explode( ',', $m[ 2 ] ) );
              }
            }
            $value = '';
            if( isset( $data[ $field ] ) ) {
              $value = $data[ $field ];
              foreach( $functions as $name => $params ) {
                if( function_exists( $name ) ) {
                  $value = call_user_func_array( $name, array_merge( array( $value ), $params ) );
                }
              }
            }
            $resolved[ $subject ] = $value;
          }
        }
      }
      return str_replace( array_keys( $resolved ), array_values( $resolved ), $this->mTplText );
    }
  }

?>