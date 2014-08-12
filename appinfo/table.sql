CREATE TABLE oc_uc_storageusage (
    "id" INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    "created" timestamp NOT NULL,
    "username" VARCHAR(255) NOT NULL,
    "usage" INT(255)
);