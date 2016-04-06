DROP DATABASE IF EXISTS %%database_name%%;
CREATE DATABASE %%database_name%% DEFAULT CHARSET 'utf8';
USE %%database_name%%;
CREATE TABLE download (
        id_download BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        modified TIMESTAMP,
        peeler VARCHAR( 255 ),
        peeler_key VARCHAR( 100 ),
        url VARCHAR( 255 ),
        renamed VARCHAR( 255 ),
        naming_status ENUM( 'naming_ok', 'naming_collision', 'naming_incomplete' ),
        INDEX idx_peeler ( peeler ),
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

CREATE PROCEDURE resetPeeler( _peelerToReset VARCHAR( 255 ) )
BEGIN
    DELETE FROM download WHERE peeler_key = _peelerToReset;
END;

CREATE PROCEDURE fetchMetadataByUrl( _url VARCHAR(255) )
BEGIN
    SELECT * FROM download WHERE url = _url;
END;

CREATE PROCEDURE getPeelerInfoStatus( _peeler_key VARCHAR( 100 ) )
BEGIN
    SELECT
        COUNT( * ) AS count_downloads,
        MAX( modified ) AS newest_download,
        MIN( modified ) AS oldest_download,
        NOW() AS data_timestamp
    FROM
        download
    WHERE
        peeler_key = _peeler_key;
END;

CREATE PROCEDURE insertMetadata(
    _url VARCHAR(255),
    _peeler VARCHAR( 255 ),
    _peeler_key VARCHAR( 100 ),
    _renamed VARCHAR( 255 ),
    _naming_status ENUM( 'naming_ok', 'naming_collision', 'naming_incomplete' ) )
BEGIN
    INSERT INTO
        download(
            url,
            peeler,
            peeler_key,
            renamed,
            naming_status )
    VALUES (
        _url,
        _peeler,
        _peeler_key,
        _renamed,
        _naming_status );
END;

CALL insertUser( 'Administrator', 'admin', '%%admin_password%%' );