CREATE TABLE oc_uc_storageusage (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    created TIMESTAMP NOT NULL,
    username VARCHAR(255) NOT NULL,
    `usage` INT NOT NULL
);