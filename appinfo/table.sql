CREATE TABLE oc_uc_storageusage (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    created TIMESTAMP NOT NULL,
    username VARCHAR(255) NOT NULL,
    `usage` INT NOT NULL
);
CREATE TABLE oc_uc_chartconfig (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    created TIMESTAMP NOT NULL,
    username VARCHAR(255) NOT NULL,
    charttype VARCHAR(255) NOT NULL,
    chartprovider VARCHAR(255) NOT NULL DEFAULT 'c3js'
);