BEGIN TRANSACTION;

        /* endpoint */
CREATE TABLE IF NOT EXISTS endpoint (
        id INTEGER PRIMARY KEY,
        customer TEXT,
        firstseen datetime, 
        lastseen datetime,      
        mac TEXT UNIQUE,
        redirecturl TEXT,      
        vendor TEXT,
        z_created datetime,
        z_updated datetime,
        z_updater TEXT DEFAULT 'system'
        );

CREATE TABLE IF NOT EXISTS master_audit (
	id integer PRIMARY KEY,
	act TEXT,
	owner TEXT,
	relation TEXT,
	tstamp datetime
);
        
CREATE TRIGGER IF NOT EXISTS endpoint_insert AFTER INSERT ON endpoint
BEGIN
   UPDATE endpoint set z_created=datetime('now'), z_updated=datetime('now') where mac=new.mac;
   INSERT INTO master_audit(act,owner,relation,tstamp) VALUES ('INSERT', new.mac, 'endpoint', datetime('now'));   
END;
CREATE TRIGGER IF NOT EXISTS endpoint_update AFTER UPDATE ON endpoint
BEGIN
   UPDATE endpoint set z_updated=datetime('now') where mac=new.mac;
   INSERT INTO master_audit(act,owner,relation,tstamp) VALUES ('UPDATE', new.mac, 'endpoint', datetime('now'));
END;
CREATE TRIGGER IF NOT EXISTS endpoint_delete AFTER DELETE ON endpoint
BEGIN
   INSERT INTO master_audit(act,owner,relation,tstamp) VALUES ('DELETE', old.mac, 'endpoint', datetime('now'));
END;
COMMIT;
