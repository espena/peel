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

CREATE TABLE user (
        id_user MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        full_name VARCHAR( 100 ),
        username VARCHAR( 50 ),
        password VARCHAR( 255 ),
        salt CHAR( 32 ),
        created TIMESTAMP,
        UNIQUE INDEX idx_username ( username )
    );

CREATE PROCEDURE insertUser( _full_name VARCHAR(100),
                             _username VARCHAR(50),
                             _password VARCHAR(255) )
BEGIN
    SET @salt = MD5( RAND() );
    INSERT INTO
        user (
            full_name,
            username,
            password,
            salt )
    VALUES (
        _full_name,
        _username,
        PASSWORD( CONCAT( _password, @salt ) ),
        @salt );
END;

CREATE PROCEDURE deleteUser( _username VARCHAR(50) )
BEGIN
    DELETE FROM user WHERE username = _username;
END;

CREATE PROCEDURE verifyUser( _username VARCHAR(50),
                             _password VARCHAR(50) )
BEGIN
    SELECT
        full_name,
        username
    FROM
        user
    WHERE
        username = _username
    AND
        password = PASSWORD( CONCAT( _password, salt ) )
    LIMIT 1;
END;

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

CALL insertUser( 'Administrator', 'admin', '%%admin_password%%' );