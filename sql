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





SELECT * FROM
trips

INNER JOIN cars ON cars.carid = trips.carid
INNER JOIN users ON users.id = cars.driverid

SELECT * FROM trips
INNER JOIN trippassengers ON trips.tripid = trippassengers.trip

INNER JOIN users ON users.id = trippassengers.passenger




SELECT * FROM trips AS t

INNER JOIN trippassengers AS tp ON t.tripid = tp.trip
INNER JOIN users AS u1 ON u1.id = tp.passenger
INNER JOIN cars AS c ON t.carid = c.carid
INNER JOIN users AS u2 ON c.driverid = u2.id




SELECT * FROM
trips AS t

INNER JOIN cars AS c ON c.carid = t.carid
RIGHT JOIN users AS u ON u.id = c.driverid
INNER JOIN trippassengers AS tp ON tp.passenger = u.id


//For tripdetails:

SELECT  t.carid, t.starttime, t.country, t.startcity, t.startstreet, t.endcity, t.endstreet, t.monday, t.tuesday, t.wednesday, t.thursday, t.friday, t.saturday, t.sunday, c.driverid, c.model, c.year, c.make, c.color, c.type, c.passengers, c.imagepath, u.first_name, u.last_name, u.email FROM
trips AS t

INNER JOIN cars AS c ON c.carid = t.carid
INNER JOIN users AS u ON u.id = c.driverid

WHERE t.tripid = 1




SELECT u.first_name, u.last_name, u.email, t.time, t.startcity, t.startstreet, t.endcity, t.endstreet FROM trippassengers AS t
INNER JOIN users AS u ON u.id = t.passenger
WHERE (t.trip = ?) AND (t.approved = 1)