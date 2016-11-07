SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `openEasySIS` ;
CREATE SCHEMA IF NOT EXISTS `openEasySIS` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `openEasySIS` ;

-- -----------------------------------------------------
-- Table `openEasySIS`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`roles` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`roles` (
  `roleID` TINYINT NOT NULL AUTO_INCREMENT ,
  `roleName` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`roleID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`users` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`users` (
  `userID` INT NOT NULL AUTO_INCREMENT ,
  `userEmail` VARCHAR(45) NOT NULL ,
  `userPassword` VARCHAR(256) NOT NULL ,
  `roleID` TINYINT NOT NULL ,
  `modProfile` TINYINT(1) NOT NULL DEFAULT false ,
  `modClassList` TINYINT(1) NOT NULL DEFAULT false ,
  `viewAllGrades` TINYINT(1) NOT NULL DEFAULT false ,
  PRIMARY KEY (`userID`) ,
  INDEX `roleID_idx` (`roleID` ASC) ,
  CONSTRAINT `roleID`
    FOREIGN KEY (`roleID` )
    REFERENCES `openEasySIS`.`roles` (`roleID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`announcements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`announcements` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`announcements` (
  `announcementID` INT NOT NULL AUTO_INCREMENT ,
  `annoucementTitle` VARCHAR(256) NOT NULL ,
  `announcementDescription` VARCHAR(2048) NOT NULL ,
  `annoucementDate` DATE NOT NULL ,
  PRIMARY KEY (`announcementID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`schoolYear`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`schoolYear` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`schoolYear` (
  `schoolYearID` INT NOT NULL AUTO_INCREMENT ,
  `fallSemesterStart` DATE NOT NULL ,
  `fallSemesterEnd` DATE NOT NULL ,
  `springSemesterStart` DATE NOT NULL ,
  `springSemesterEnd` DATE NOT NULL ,
  `quarterOneStart` DATE NOT NULL ,
  `quarterOneEnd` DATE NOT NULL ,
  `quarterTwoStart` DATE NOT NULL ,
  `quarterTwoEnd` DATE NOT NULL ,
  `quarterThreeStart` DATE NOT NULL ,
  `quarterThreeEnd` DATE NOT NULL ,
  `schoolYearStart` DATE NOT NULL ,
  `schoolYearEnd` DATE NOT NULL ,
  PRIMARY KEY (`schoolYearID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`studentProfile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`studentProfile` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`studentProfile` (
  `studentID` INT NOT NULL ,
  `studentFirstName` VARCHAR(45) NOT NULL ,
  `studentLastName` VARCHAR(45) NOT NULL ,
  `studentAddress` VARCHAR(256) NOT NULL ,
  `studentCity` VARCHAR(45) NOT NULL ,
  `studentState` VARCHAR(2) NOT NULL ,
  `studentZip` VARCHAR(5) NOT NULL ,
  `studentBirthdate` DATETIME NOT NULL ,
  `studentEmergencyNumber` VARCHAR(11) NOT NULL ,
  `studentGuardianIDs` VARCHAR(2048) NOT NULL ,
  `studentGender` VARCHAR(1) NOT NULL ,
  `studentGradYear` DATETIME NOT NULL ,
  `studentGPA` FLOAT NOT NULL ,
  `studentGradeLevel` TINYINT NOT NULL ,
  `studentClassIDs` VARCHAR(2048) NOT NULL ,
  PRIMARY KEY (`studentID`) ,
  CONSTRAINT `studentID`
    FOREIGN KEY (`studentID` )
    REFERENCES `openEasySIS`.`users` (`userID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`teacherProfile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`teacherProfile` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`teacherProfile` (
  `teacherID` INT NOT NULL AUTO_INCREMENT ,
  `teacherFirstName` VARCHAR(45) NOT NULL ,
  `teacherLastName` VARCHAR(45) NOT NULL ,
  `teacherEmail` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`teacherID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`classes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`classes` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`classes` (
  `classID` INT NOT NULL AUTO_INCREMENT ,
  `classGrade` INT NOT NULL ,
  `className` VARCHAR(512) NOT NULL ,
  `classStudentNumber` INT NULL ,
  `classTeacherID` INT NOT NULL ,
  PRIMARY KEY (`classID`) ,
  INDEX `teacherID_idx` (`classTeacherID` ASC) ,
  CONSTRAINT `teacherID`
    FOREIGN KEY (`classTeacherID` )
    REFERENCES `openEasySIS`.`teacherProfile` (`teacherID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`materialType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`materialType` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`materialType` (
  `materialTypeID` INT NOT NULL AUTO_INCREMENT ,
  `materialName` VARCHAR(45) NOT NULL ,
  `classID` INT NOT NULL ,
  `materialWeight` INT NOT NULL ,
  PRIMARY KEY (`materialTypeID`) ,
  INDEX `classID_idx` (`classID` ASC) ,
  CONSTRAINT `classID`
    FOREIGN KEY (`classID` )
    REFERENCES `openEasySIS`.`classes` (`classID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`materials`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`materials` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`materials` (
  `materialID` INT NOT NULL AUTO_INCREMENT ,
  `materialClassID` INT NOT NULL ,
  `materialName` VARCHAR(45) NOT NULL ,
  `materialPointsPossible` INT NOT NULL ,
  `materialDateDue` DATE NULL ,
  `materialTypeID` INT NOT NULL ,
  PRIMARY KEY (`materialID`) ,
  INDEX `materialTypeID_idx` (`materialTypeID` ASC) ,
  CONSTRAINT `materialTypeID`
    FOREIGN KEY (`materialTypeID` )
    REFERENCES `openEasySIS`.`materialType` (`materialTypeID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`grades`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`grades` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`grades` (
  `gradeStudentID` INT NOT NULL ,
  `gradeClassID` INT NOT NULL ,
  `gradeMaterialID` INT NOT NULL ,
  `gradeMaterialPointsScored` INT NULL ,
  `gradeComments` VARCHAR(512) NULL ,
  PRIMARY KEY (`gradeStudentID`) ,
  INDEX `classID_idx` (`gradeClassID` ASC) ,
  INDEX `materialID_idx` (`gradeMaterialID` ASC) ,
  CONSTRAINT `gradeClassID`
    FOREIGN KEY (`gradeClassID` )
    REFERENCES `openEasySIS`.`classes` (`classID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `gradeStudentID`
    FOREIGN KEY (`gradeStudentID` )
    REFERENCES `openEasySIS`.`studentProfile` (`studentID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `gradeMaterialID`
    FOREIGN KEY (`gradeMaterialID` )
    REFERENCES `openEasySIS`.`materialType` (`materialTypeID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `openEasySIS` ;

SET SQL_MODE = '';
GRANT USAGE ON *.* TO dbSISAccessor;
 DROP USER dbSISAccessor;
SET SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
CREATE USER 'dbSISAccessor' IDENTIFIED BY 'dbSISAccessor';

GRANT SELECT, INSERT, TRIGGER, UPDATE, DELETE, ALTER ON TABLE `openEasySIS`.* TO 'dbSISAccessor';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`roles`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`roles` (`roleID`, `roleName`) VALUES (1, 'Administrator');
INSERT INTO `openEasySIS`.`roles` (`roleID`, `roleName`) VALUES (2, 'School Administrator');
INSERT INTO `openEasySIS`.`roles` (`roleID`, `roleName`) VALUES (3, 'Teacher');
INSERT INTO `openEasySIS`.`roles` (`roleID`, `roleName`) VALUES (4, 'Parent');
INSERT INTO `openEasySIS`.`roles` (`roleID`, `roleName`) VALUES (5, 'Student');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`) VALUES (1, 'admin@localhost.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1, 1, 1, 1);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`) VALUES (2, 'schoolAdmin@localhost.com', 'bc2f7e911ed8ca5d4201b099689db41f42f4a654fb47fb64cdcab25595185c82', 2, 1, 0, 1);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`) VALUES (3, 'teacher@localhost.com', '1057a9604e04b274da5a4de0c8f4b4868d9b230989f8c8c6a28221143cc5a755', 3, 0, 1, 0);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`) VALUES (4, 'parent@localhost.com', 'e47125968b3b71049fbc4802d1e40a71ea1359decfabacf70b34588037d4ff0c', 4, 0, 0, 0);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`) VALUES (5, 'student@localhost.com', '264c8c381bf16c982a4e59b0dd4c6f7808c51a05f64c35db42cc78a2a72875bb', 5, 0, 0, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`announcements`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`announcements` (`announcementID`, `annoucementTitle`, `announcementDescription`, `annoucementDate`) VALUES (1, 'Announcement Test', 'This is a test of an announcement made and be displayed', '2016-11-06');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`schoolYear`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`schoolYear` (`schoolYearID`, `fallSemesterStart`, `fallSemesterEnd`, `springSemesterStart`, `springSemesterEnd`, `quarterOneStart`, `quarterOneEnd`, `quarterTwoStart`, `quarterTwoEnd`, `quarterThreeStart`, `quarterThreeEnd`, `schoolYearStart`, `schoolYearEnd`) VALUES (1, '2016-09-01', '2016-12-16', '2017-01-10', '2017-05-21', '2016-09-01', '2016-10-26', '2016-10-27', '2016-12-16', '2017-01-10', '2017-05-21', '2017', '2018');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`studentProfile`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`studentProfile` (`studentID`, `studentFirstName`, `studentLastName`, `studentAddress`, `studentCity`, `studentState`, `studentZip`, `studentBirthdate`, `studentEmergencyNumber`, `studentGuardianIDs`, `studentGender`, `studentGradYear`, `studentGPA`, `studentGradeLevel`, `studentClassIDs`) VALUES (5, 'Test', 'Student', '11111 West Student Road', 'stuCity', 'MI', '49783', '1995-11-06', '9062485555', '3', 'M', '2018', 3.68, 11, '1');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`teacherProfile`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`teacherProfile` (`teacherID`, `teacherFirstName`, `teacherLastName`, `teacherEmail`) VALUES (3, 'Mrs.', 'Teacher', 'teacher@localhost.com');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`classes`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classStudentNumber`, `classTeacherID`) VALUES (1, 11, 'Intro to Business', 1, 3);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`materialType`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (1, 'Homework', 1, 0);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (2, 'Quizzes', 1, 0);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (3, 'Exams', 1, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`materials`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDateDue`, `materialTypeID`) VALUES (1, 1, 'Test Assignment', 400, '2017-01-24', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`grades`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeComments`) VALUES (5, 1, 1, 400, NULL);

COMMIT;
