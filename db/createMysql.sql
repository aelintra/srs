
CREATE DATABASE IF NOT EXISTS macdb;

GRANT SELECT, INSERT, UPDATE, DELETE  
  ON macdb.* 
  TO srsuser@localhost 
  IDENTIFIED BY 'aster1sk'; 

use macdb;

        /* endpoint */
CREATE TABLE IF NOT EXISTS endpoint (
    id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    customer VARCHAR(50),
    firstseen DATETIME,
    lastseen DATETIME,
    mac VARCHAR(12),
    redirecturl VARCHAR(120),      
    vendor VARCHAR(20),
    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater CHAR(20)
    );
CREATE UNIQUE INDEX idx_mac ON endpoint(mac); 

CREATE TABLE IF NOT EXISTS master_audit (
	id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	act VARCHAR(20),
	owner VARCHAR(20),
	relation VARCHAR(50),
	tstamp TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

DELIMITER //        
CREATE TRIGGER endpoint_insert BEFORE INSERT ON endpoint
	FOR EACH ROW  
	BEGIN
   		INSERT INTO master_audit (act,owner,relation) VALUES ('INSERT',new.mac,'endpoint');  
	END;//
DELIMITER ;

DELIMITER // 
CREATE TRIGGER endpoint_update AFTER UPDATE ON endpoint
	FOR EACH ROW  
	BEGIN
   		INSERT INTO master_audit(act,owner,relation) VALUES ('UPDATE',new.mac,'endpoint');
	END;//
DELIMITER ;

DELIMITER // 	
CREATE TRIGGER endpoint_delete AFTER DELETE ON endpoint
	FOR EACH ROW  
	BEGIN
   		INSERT INTO master_audit(act,owner,relation) VALUES ('DELETE',old.mac,'endpoint');
	END;//
DELIMITER ;
