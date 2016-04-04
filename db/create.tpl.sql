DROP DATABASE IF EXISTS %%database%%;
CREATE DATABASE %%database%% DEFAULT CHARSET 'utf8';
USE %%database%%;
CREATE TABLE download (
        id_download BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        modified TIMESTAMP,
        peeler VARCHAR( 255 ),
        url VARCHAR( 255 ),
        renamed VARCHAR( 255 ),
        naming_status ENUM( 'naming_ok', 'naming_collision', 'naming_incomplete' ),
        INDEX idx_url ( url )
    );

CREATE PROCEDURE fetchMetadataByUrl( _url VARCHAR(255) )
BEGIN
    SELECT * FROM download WHERE url = _url;
END;

CREATE PROCEDURE insertMetadata(
    _url VARCHAR(255),
    _peeler VARCHAR( 255 ),
    _renamed VARCHAR( 255 ),
    _naming_status ENUM( 'naming_ok', 'naming_collision', 'naming_incomplete' ) )
BEGIN
    INSERT INTO download( url, peeler, renamed, naming_status ) VALUES ( _url, _peeler, _renamed, _naming_status );
END;

