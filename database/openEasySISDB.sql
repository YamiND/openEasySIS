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
  `userSalt` VARCHAR(256) NOT NULL ,
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
  `studentBirthdate` DATETIME NOT NULL ,
  `studentGuardianIDs` VARCHAR(2048) NOT NULL ,
  `studentGender` VARCHAR(1) NOT NULL ,
  `studentGradYear` YEAR NOT NULL ,
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
  PRIMARY KEY (`teacherID`) ,
  CONSTRAINT `teacherID`
    FOREIGN KEY (`teacherID` )
    REFERENCES `openEasySIS`.`users` (`userID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
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
  INDEX `classTeacherID_idx` (`classTeacherID` ASC) ,
  CONSTRAINT `classTeacherID`
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
  `gradeMaterialPointsScored` INT NULL DEFAULT 0 ,
  `gradeComments` VARCHAR(512) NULL DEFAULT '' ,
  `gradeRefID` INT NOT NULL AUTO_INCREMENT ,
  INDEX `classID_idx` (`gradeClassID` ASC) ,
  INDEX `materialID_idx` (`gradeMaterialID` ASC) ,
  PRIMARY KEY (`gradeRefID`) ,
  CONSTRAINT `gradeClassID`
    FOREIGN KEY (`gradeClassID` )
    REFERENCES `openEasySIS`.`classes` (`classID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `gradeMaterialID`
    FOREIGN KEY (`gradeMaterialID` )
    REFERENCES `openEasySIS`.`materialType` (`materialTypeID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`guardianProfile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`guardianProfile` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`guardianProfile` (
  `guardianID` INT NOT NULL ,
  `guardianFirstName` VARCHAR(45) NOT NULL ,
  `guardianLastName` VARCHAR(45) NOT NULL ,
  `guardianEmail` VARCHAR(45) NOT NULL ,
  `guardianPhoneNumber` VARCHAR(1) NOT NULL ,
  `guardianAltEmail` VARCHAR(45) NULL ,
  `guardianAddress` VARCHAR(100) NOT NULL ,
  `guardianCity` VARCHAR(45) NOT NULL ,
  `guardianState` VARCHAR(2) NOT NULL ,
  `guardianZip` VARCHAR(5) NOT NULL ,
  PRIMARY KEY (`guardianID`) ,
  CONSTRAINT `guardianID`
    FOREIGN KEY (`guardianID` )
    REFERENCES `openEasySIS`.`users` (`userID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`adminProfile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`adminProfile` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`adminProfile` (
  `adminID` INT NOT NULL ,
  `adminFirstName` VARCHAR(45) NULL ,
  `adminLastName` VARCHAR(45) NULL ,
  `adminEmail` VARCHAR(45) NULL ,
  PRIMARY KEY (`adminID`) ,
  CONSTRAINT `adminID`
    FOREIGN KEY (`adminID` )
    REFERENCES `openEasySIS`.`users` (`userID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`schoolAdminProfile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`schoolAdminProfile` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`schoolAdminProfile` (
  `schoolAdminID` INT NOT NULL ,
  `schoolAdminFirstName` VARCHAR(45) NULL ,
  `schoolAdminLastName` VARCHAR(45) NULL ,
  `schoolAdminEmail` VARCHAR(45) NULL ,
  PRIMARY KEY (`schoolAdminID`) ,
  CONSTRAINT `schoolAdminID`
    FOREIGN KEY (`schoolAdminID` )
    REFERENCES `openEasySIS`.`users` (`userID` )
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
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`, `userSalt`) VALUES (1, 'admin@localhost.com', '7fb543627c22b84597ca27d748e5598a0806ac124b762cee25643cdede10add7e85ae57615a2647efc50c3aa54d54e6bafc57f5b8d2ac6fb43f280ae2b89b02d', 1, 1, 1, 1, '46d5eb8476b910c3501188f91f4fedfd593d8a7b13c27e25c34fd683297f43fd79f4f79cd87c9eaccdee8ed636adef49a461f1591013c7face1081191f5deb38');
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`, `userSalt`) VALUES (2, 'schoolAdmin@localhost.com', 'b1aae2b74de38745e25b9f232b761dd9560d95ce05af9faf71a6da8edd6fd80db00abefe2a03fd93b594182b07ac60db8dd3b80ff0cce5fbf3fc87351810d1f2', 2, 1, 0, 1, '8aab3f6290cc4a3d5ce6dce0f4a1ab75340424e58e645dc0c26c4815f82b17ff13d5cf599b225f9102f7f0db0903163f2ad7273863f26d916d2ea7381fbe1821');
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`, `userSalt`) VALUES (3, 'teacher@localhost.com', '88335388382d6a9f2ee9e7848804097ac544970a5c1df4c99d1c6d4eb3c34c6a585e155351bb71cd9fa793225c2245a1c11ef3b90117a1711f16b7b0689d68b5', 3, 0, 1, 0, '49e907b72ecf7469a56eb1daa6e1ed55a4a936eb4780ecc3fab0f6e7510fcc7f652fc4e23cbc5b4bb42e237833e99f4fd9209853644921c470bfaaa0d847a1fe');
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`, `userSalt`) VALUES (4, 'parent@localhost.com', '307ab73a0f7286afe1a5b610334ebb8913002828c836b27938d98181e597f4c373790e8001c3843b46513b43131659d383f3f7aaedbc321f0e7ae06ba8550939', 4, 0, 0, 0, '780f4ac261a1243a693dcf7ad9142b42d745e2ce36d102284b0b9e12b13b03e96e36b347620fad9b9fa4a019919653532b2e1c3e1dd9cc524f99080a34efa119');
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `roleID`, `modProfile`, `modClassList`, `viewAllGrades`, `userSalt`) VALUES (5, 'student@localhost.com', '0f27d1bacc6964f6b5318de2ef18bce5e1af16720f0a74fd761f0e7bf5adcae853b6ae1dff71dce79f341137cfda542dad917d6e98786210ae6c0202905085a9', 5, 0, 0, 0, 'b56067c035fc029daf6b13734df79666d418f35c4e94d8e112a65b1ae07161d30514a1457d17c9a5dbecf346479f31ab13bbdc04b42b88057de2c41050f1e574');

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
INSERT INTO `openEasySIS`.`studentProfile` (`studentID`, `studentFirstName`, `studentLastName`, `studentBirthdate`, `studentGuardianIDs`, `studentGender`, `studentGradYear`, `studentGPA`, `studentGradeLevel`, `studentClassIDs`) VALUES (5, 'Test', 'Student', '1995-11-06', '3', 'M', 2018, 3.68, 11, '1');

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
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeComments`, `gradeRefID`) VALUES (5, 1, 1, 400, NULL, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`guardianProfile`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`guardianProfile` (`guardianID`, `guardianFirstName`, `guardianLastName`, `guardianEmail`, `guardianPhoneNumber`, `guardianAltEmail`, `guardianAddress`, `guardianCity`, `guardianState`, `guardianZip`) VALUES (4, 'Test', 'Parent', 'parent@localhost.com', '9062485555', 'altParent@localhost.com', '11444 West Test Road', 'Sault Ste Marie', 'MI', '49783');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`adminProfile`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`adminProfile` (`adminID`, `adminFirstName`, `adminLastName`, `adminEmail`) VALUES (1, 'Administrator', 'Administrator', 'admin@localhost.com');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`schoolAdminProfile`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`schoolAdminProfile` (`schoolAdminID`, `schoolAdminFirstName`, `schoolAdminLastName`, `schoolAdminEmail`) VALUES (2, 'School', 'Administrator', 'schoolAdmin@localhost.com');

COMMIT;
