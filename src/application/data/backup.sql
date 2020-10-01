PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties`(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  `address_street` TEXT,
  `street_index` TEXT,
  `google_index` TEXT,
  `address_suburb` TEXT,
  `address_state` TEXT,
  `address_postcode` TEXT,
  `description_type` TEXT,
  `description_beds` TEXT,
  `description_bath` INTEGER DEFAULT 0,
  `description_car` INTEGER DEFAULT 0,
  `created` TEXT,
  `updated` TEXT);
INSERT INTO properties VALUES(1,'705/15 Haig Street',NULL,NULL,'KIRRA','QLD','4225','Unit','2',2,2,'2020-07-30 15:32:46','2020-07-30 15:48:12');
INSERT INTO properties VALUES(2,'109 Buckingham Street',NULL,NULL,'ASHGROVE','QLD','4060','House','4',3,6,'2020-07-30 15:33:17','2020-07-30 15:48:05');
COMMIT;
