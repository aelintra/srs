CREATE DATABASE IF NOT EXISTS macdb;

GRANT SELECT, INSERT, UPDATE, DELETE  
  ON macdb.* 
  TO srsuser@localhost 
  IDENTIFIED BY 'aster1sk'; 

use macdb;

CREATE TABLE IF NOT EXISTS customer (
    pkey VARCHAR(64) NOT NULL,          /* Customer name/number */ 
    parent_pkey VARCHAR(64),            /* parent Foreign key */ 

    dflt_user_pass VARCHAR(32),         /* this is for the phone browser */
    dflt_admin_pass VARCHAR(32),        /* this is for the phone browser */ 
    dflt_redirect VARCHAR(2047),        /* default redirect target. New endpoints will get this but it can be overridden in the endpoint */     
    dflt_sip_reg VARCHAR(2047),         /* default SIP Registration URL. New endpoints will get this but it can be overridden in the endpoint */

    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(32) DEFAULT 'system',

    PRIMARY KEY (pkey),
    FOREIGN KEY (parent_pkey) REFERENCES customer(pkey) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE        
); 
CREATE INDEX idx_customer_parent_pkey ON customer(parent_pkey);

CREATE TABLE IF NOT EXISTS endpoint (
    pkey VARCHAR(12) NOT NULL,          /* MAC address */
    customer_pkey VARCHAR(50),          /* Customer foreign key */
   
    displayName VARCHAR(32),            /* Phone display name */
    devicemodel VARCHAR(32),            /* Harvested device model */
    provision TEXT,                     /* root provisioning directives copied from root template */
    redirecturl VARCHAR(2047),          /* redirect target */ 
    mode TINYINT(1) DEFAULT 1,          /* 0 => Inactive, 1 => Redirect, 2 => Provision */  
    sip_account VARCHAR(32),            /* SIP peer name (extension)  */ 
    sip_pass VARCHAR(32),               /* SIP password */
    sip_reg VARCHAR(2047),              /* SIP Registration URL */
    transport VARCHAR(4),               /* UDP,TCP,TLS */ 
    vendor VARCHAR(32),                 /* Taken from MAC lookup on MAC DB */  

    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_firstseen DATETIME,
    z_lastseen DATETIME,
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(32) DEFAULT 'system',


    PRIMARY KEY (pkey),
    FOREIGN KEY (customer_pkey) REFERENCES customer(pkey)
    ON DELETE SET NULL
    ON UPDATE CASCADE    
);
CREATE INDEX idx_endpoint_customer_pkey ON endpoint(customer_pkey);

CREATE TABLE IF NOT EXISTS template (
    pkey VARCHAR(64) NOT NULL,          /* Template name */
    parent_pkey VARCHAR(64),            /* parent Foreign key */
    imageurl VARCHAR(2047),             /* Pointer to an image of the device if this is head of chain */  
    owner VARCHAR(20) DEFAULT 'system',
    provision TEXT,                     /* provisioning directives */
    technology VARCHAR(32),             /* SIP/Descriptor/BLF Template */

    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(20) DEFAULT 'system',

    PRIMARY KEY (pkey),
    FOREIGN KEY (parent_pkey) REFERENCES template(pkey) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE     
);
CREATE INDEX idx_template_parent_pkey ON template(pkey); 
