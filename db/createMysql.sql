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

/* Customer Head of chain or customerPBX instance */
/* create one customer head of chain for each customer and */
/* one or more customerPBX children */

CREATE TABLE IF NOT EXISTS customer (
    pkey VARCHAR(64) NOT NULL DEFAULT 'system',        /* Customer name/number */ 
    parent VARCHAR(64),                                /* parent Foreign key */ 

    ep_user VARCHAR(32) DEFAULT 'user',                /* this is for the phone browser */
    ep_user_pass VARCHAR(32) DEFAULT 'myuserpass',     /* this is for the phone browser */
    ep_admin_pass VARCHAR(32) DEFAULT 'myadminpass',   /* this is for the phone browser */ 

    provurl VARCHAR(2047),        /* provisioning url to set in the phone */
    redirecturl VARCHAR(2047),    /* default HTTP/HTTPS redirect target for this PBX */
                                  /* You can override this at the endpoint if you have */
                                  /* multiple provisioning instances */

    sipurl VARCHAR(2047),         /* SIP Registration URL for this PBX */

    ldapbase VARCHAR(2047),            /* default LDAP base */
    ldaphost VARCHAR(2047),            /* default LDAP host */
    ldappass VARCHAR(32),            /* default LDAP pwd */    
    ldapuser VARCHAR(32),            /* default LDAP user */

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
    pkey VARCHAR(64) NOT NULL,                        /* Template name */
    parent VARCHAR(64),                               /* parent Foreign key */
    customer VARCHAR(64) NOT NULL DEFAULT 'system',   /* Template owner */
    
    provision TEXT,                     /* provisioning directives */

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
    customer VARCHAR(64),                   /* Customer PBX foreign key */
    customtemplate VARCHAR(64),             /* Custom template overrides vendor template */
    device VARCHAR(64),                     /* Autofill device model */   

    displayname VARCHAR(32),                /* Phone display name */ 
    mode TINYINT(1) DEFAULT 1,              /* 0 => Inactive, 1 => Redirect, 2 => Provision */
    provision TEXT,                         /* Autofill from template */
    redirecturl VARCHAR(2047),              /* redirect target (overrides pbx redirect) */       
    sipaccount VARCHAR(32),                 /* SIP peer name (extension number)  */ 
    sippass VARCHAR(32),                    /* SIP password */
    vendor VARCHAR(32),                     /* Autofill from MAC lookup on MAC DB */  
                                            /* Base template will be copied from this */
                                            /* you can override it with a customtemplate  */

    z_created DATETIME DEFAULT CURRENT_TIMESTAMP,
    z_firstseen DATETIME,                   /* first request date  */
    z_lastseen DATETIME,                    /* latest request date */
    z_lastloc VARCHAR(32),                  /* last request origin */
    z_updated TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    z_updater VARCHAR(32) DEFAULT 'system',

    PRIMARY KEY (pkey),
    
    FOREIGN KEY (customer) REFERENCES customer(pkey)
    ON DELETE SET NULL
    ON UPDATE CASCADE, 
    
    INDEX idx_endpoint_customer (customer)
      
);