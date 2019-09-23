CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  first_name varchar(25) NOT NULL,
  last_name varchar(25) NOT NULL,
  email varchar(65) NOT NULL,
  username varchar(65) NOT NULL,
  password text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;



CREATE TABLE cars (
    carid varchar(10) NOT NULL,
    driverid int(11) DEFAULT NULL,
    model varchar(50) NOT NULL,
    year int(10) NOT NULL,
    make varchar(30) NOT NULL,
    color varchar(30) NOT NULL,
    type varchar(30) NOT NULL,
    passengers tinyint(4) UNSIGNED NOT NULL,
    imagepath text NOT NULL,
    PRIMARY KEY (carid),
    CONSTRAINT driveruserkey FOREIGN KEY (driverid) REFERENCES users(id)
) ENGINE=InnoDB;



CREATE TABLE trips (
  tripid int(11) NOT NULL AUTO_INCREMENT,
  carid varchar(10) NOT NULL,
  starttime time NOT NULL,
  country varchar(50) NOT NULL,
  startcity varchar(50) NOT NULL,
  startstreet varchar(50) NOT NULL,
  endcity varchar(50) NOT NULL,
  endstreet varchar(50) NOT NULL,
  monday tinyint(1) DEFAULT NULL,
  tuesday tinyint(1) DEFAULT NULL,
  wednesday tinyint(1) DEFAULT NULL,
  thursday tinyint(1) DEFAULT NULL,
  friday tinyint(1) DEFAULT NULL,
  saturday tinyint(1) DEFAULT NULL,
  sunday tinyint(1) DEFAULT NULL,
  PRIMARY KEY (tripid),
  CONSTRAINT tripcaridkey FOREIGN KEY (carid) REFERENCES cars(carid)
) ENGINE=InnoDB;




CREATE TABLE trippassengers (
  trip int(11) NOT NULL,
  passenger int(11) NOT NULL,
  time time NOT NULL,
  startcity varchar(50) NOT NULL,
  startstreet varchar(50) NOT NULL,
  endcity varchar(50) NOT NULL,
  endstreet varchar(50) NOT NULL,
  approved tinyint(2) NOT NULL,
  PRIMARY KEY (trip,passenger),
  CONSTRAINT passengerid FOREIGN KEY (passenger) REFERENCES users(id),
  CONSTRAINT tripid FOREIGN KEY (trip) REFERENCES trips(tripid)
) ENGINE=InnoDB;