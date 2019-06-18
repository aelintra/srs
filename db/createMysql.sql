CREATE DATABASE IF NOT EXISTS macdb;

GRANT SELECT, INSERT, UPDATE, DELETE  
  ON macdb.* 
  TO srsuser@localhost 
  IDENTIFIED BY 'aster1sk'; 

GRANT ALL PRIVILEGES 
  ON macdb.* 
  TO srsadmin@localhost 
  IDENTIFIED BY 'Aster3sk@'; 

use macdb;

CREATE TABLE IF NOT EXISTS customer (
    pkey VARCHAR(64) NOT NULL DEFAULT 'system',          /* Customer name/number */ 
    parent VARCHAR(64),                 /* parent Foreign key */ 

    dflt_ep_user VARCHAR(32),           /* this is for the phone browser */
    dflt_ep_user_pass VARCHAR(32),      /* this is for the phone browser */
    dflt_ep_admin_pass VARCHAR(32),     /* this is for the phone browser */ 

    dflt_redirecturl VARCHAR(2047),     /* default redirect target */     
    dflt_sip_reg VARCHAR(2047),         /* default SIP Registration URL */

    ldap_base VARCHAR(2047),            /* default LDAP base */
    ldap_host VARCHAR(2047),            /* default LDAP host */
    ldap_pass VARCHAR(2047),            /* default LDAP pwd */    
    ldap_user VARCHAR(2047),            /* default LDAP user */

    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(32) DEFAULT 'system',

    PRIMARY KEY (pkey),

    FOREIGN KEY (parent) REFERENCES customer(pkey) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE,

    INDEX idx_customer_parent (parent)       
); 

CREATE TABLE IF NOT EXISTS template (
    pkey VARCHAR(64) NOT NULL,          /* Template name */
    parent VARCHAR(64),            /* parent Foreign key */
    customer VARCHAR(64) NOT NULL DEFAULT 'system',   /* Template owner */
    
    provision TEXT,                     /* provisioning directives */
    technology VARCHAR(32),             /* SIP/Descriptor/BLF Template */

    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(20) DEFAULT 'system',

    PRIMARY KEY (pkey),

    FOREIGN KEY (customer) REFERENCES customer(pkey) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE,    
    
    FOREIGN KEY (parent) REFERENCES template(pkey) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE,   
    
    INDEX idx_template_parent (pkey),
    INDEX idx_template_customer (customer)    
);

CREATE TABLE IF NOT EXISTS device (
    pkey VARCHAR(64) NOT NULL,          /* Device name */
    imageurl VARCHAR(2047),             /* Image URL */
 
    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(20) DEFAULT 'system',

    PRIMARY KEY (pkey)

);

CREATE TABLE IF NOT EXISTS endpoint (
    pkey VARCHAR(12) NOT NULL,              /* MAC address */
    customer VARCHAR(64),                   /* Customer foreign key */
    template VARCHAR(64),                   /* Template chain base name */
    device VARCHAR(64),                     /* Autofill device model */

    displayname VARCHAR(32),                /* Phone display name */ 
    devicemodel VARCHAR(64),                /* Autofill device model */
    mode TINYINT(1) DEFAULT 1,              /* 0 => Inactive, 1 => Redirect, 2 => Provision */
    redirecturl VARCHAR(2047),              /* redirect target */       
    sip_account VARCHAR(32),                /* SIP peer name (extension)  */ 
    sip_pass VARCHAR(32),                   /* SIP password */
    sip_reg VARCHAR(2047),                  /* SIP Registration URL */ 
    vendor VARCHAR(32),                     /* Autofill from MAC lookup on MAC DB */  

    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_firstseen DATETIME,               /* first request date */
    z_lastseen DATETIME,                /* latest request date */
    z_lastloc VARCHAR(32),              /* last request origin */
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(32) DEFAULT 'system',

    PRIMARY KEY (pkey),
    
    FOREIGN KEY (customer) REFERENCES customer(pkey)
    ON DELETE SET NULL
    ON UPDATE CASCADE,

    FOREIGN KEY (template) REFERENCES template(pkey)
    ON DELETE SET NULL
    ON UPDATE CASCADE,

    FOREIGN KEY (device) REFERENCES device(pkey) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE, 
    
    INDEX idx_endpoint_customer (customer),
    INDEX idx_endpoint_template (template)    
);