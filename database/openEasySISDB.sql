SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `openEasySIS` ;
CREATE SCHEMA IF NOT EXISTS `openEasySIS` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `openEasySIS` ;

-- -----------------------------------------------------
-- Table `openEasySIS`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`users` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`users` (
  `userID` INT NOT NULL AUTO_INCREMENT ,
  `userEmail` VARCHAR(45) NOT NULL ,
  `userPassword` VARCHAR(256) NOT NULL ,
  `userFirstName` VARCHAR(45) NOT NULL ,
  `userLastName` VARCHAR(45) NOT NULL ,
  `modClassList` TINYINT(1) NOT NULL DEFAULT false ,
  `viewAllGrades` TINYINT(1) NOT NULL DEFAULT false ,
  `userSalt` VARCHAR(256) NOT NULL ,
  `isParent` TINYINT(1) NOT NULL ,
  `isStudent` TINYINT(1) NOT NULL ,
  `isTeacher` TINYINT(1) NOT NULL ,
  `isSchoolAdmin` TINYINT(1) NOT NULL ,
  `isAdmin` TINYINT(1) NOT NULL ,
  `studentGPA` FLOAT NULL ,
  `studentGradeLevel` TINYINT NULL ,
  `parentAddress` VARCHAR(200) NULL ,
  `parentPhone` VARCHAR(14) NULL ,
  `isPrincipal` TINYINT(1) NULL DEFAULT false ,
  PRIMARY KEY (`userID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`announcements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`announcements` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`announcements` (
  `announcementID` INT NOT NULL AUTO_INCREMENT ,
  `announcementName` VARCHAR(256) NOT NULL ,
  `announcementDescription` VARCHAR(2048) NOT NULL ,
  `announcementPostDate` DATE NOT NULL ,
  `announcementEndDate` DATE NULL ,
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
  `quarterFourStart` DATE NOT NULL ,
  `quarterFourEnd` DATE NOT NULL ,
  `schoolYearStart` DATE NOT NULL ,
  `schoolYearEnd` DATE NOT NULL ,
  PRIMARY KEY (`schoolYearID`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`classes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`classes` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`classes` (
  `classID` INT NOT NULL AUTO_INCREMENT ,
  `classGrade` INT NOT NULL ,
  `className` VARCHAR(512) NOT NULL ,
  `classTeacherID` INT NOT NULL ,
  `schoolYearID` INT NOT NULL ,
  PRIMARY KEY (`classID`) )
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
  `materialDueDate` DATE NULL ,
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
  `gradeRefID` INT NOT NULL AUTO_INCREMENT ,
  INDEX `classID_idx` (`gradeClassID` ASC) ,
  PRIMARY KEY (`gradeRefID`) ,
  INDEX `gradeMaterialID_idx` (`gradeMaterialID` ASC) ,
  CONSTRAINT `gradeClassID`
    FOREIGN KEY (`gradeClassID` )
    REFERENCES `openEasySIS`.`classes` (`classID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `gradeMaterialID`
    FOREIGN KEY (`gradeMaterialID` )
    REFERENCES `openEasySIS`.`materials` (`materialID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`studentClassIDs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`studentClassIDs` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`studentClassIDs` (
  `studentID` INT NOT NULL ,
  `classID` INT NOT NULL )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `openEasySIS`.`studentParentIDs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `openEasySIS`.`studentParentIDs` ;

CREATE  TABLE IF NOT EXISTS `openEasySIS`.`studentParentIDs` (
  `studentID` INT NOT NULL ,
  `parentID` INT NOT NULL )
ENGINE = InnoDB;

USE `openEasySIS` ;

SET SQL_MODE = '';
GRANT USAGE ON *.* TO dbSISAccessor;
 DROP USER dbSISAccessor;
SET SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
CREATE USER 'dbSISAccessor' IDENTIFIED BY 'dbSISAccessor';

GRANT ALL ON `openEasySIS`.* TO 'dbSISAccessor';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `modClassList`, `viewAllGrades`, `userSalt`, `isParent`, `isStudent`, `isTeacher`, `isSchoolAdmin`, `isAdmin`, `studentGPA`, `studentGradeLevel`, `parentAddress`, `parentPhone`, `isPrincipal`) VALUES (1, 'admin@localhost.com', '506beb3bb6a2c033392158f6451e85d5862b9997c999a3438cd3c4e93d65e7bf5b205f5cd132724f1db7ef9bd94088a72f9b9417c829cf081fffc3c2c599496f', 'Admin', 'User', 1, 1, '46d5eb8476b910c3501188f91f4fedfd593d8a7b13c27e25c34fd683297f43fd79f4f79cd87c9eaccdee8ed636adef49a461f1591013c7face1081191f5deb38', false, false, false, false, true, NULL, NULL, NULL, NULL, 0);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `modClassList`, `viewAllGrades`, `userSalt`, `isParent`, `isStudent`, `isTeacher`, `isSchoolAdmin`, `isAdmin`, `studentGPA`, `studentGradeLevel`, `parentAddress`, `parentPhone`, `isPrincipal`) VALUES (2, 'schoolAdmin@localhost.com', '19145a6a9e87e75ad550622552e088ec786b869e8b2c7a203a502171bba59b1cb9284af2f1b63bbf625e0597e85775ffb1f97b6a54f11fed408b8b45d5991a5b', 'SchoolAdmin', 'User', 0, 1, '8aab3f6290cc4a3d5ce6dce0f4a1ab75340424e58e645dc0c26c4815f82b17ff13d5cf599b225f9102f7f0db0903163f2ad7273863f26d916d2ea7381fbe1821', false, false, false, true, false, NULL, NULL, NULL, NULL, 1);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `modClassList`, `viewAllGrades`, `userSalt`, `isParent`, `isStudent`, `isTeacher`, `isSchoolAdmin`, `isAdmin`, `studentGPA`, `studentGradeLevel`, `parentAddress`, `parentPhone`, `isPrincipal`) VALUES (3, 'teacher@localhost.com', '0cbb9145e706c497d95aebcea57c6e8763a9596623ec749c3ca0015028db6b24681a4f1c38254c57674ee161a9c4ccf14dd1024129590d656809eb5df9408935', 'Teacher', 'User', 1, 0, '49e907b72ecf7469a56eb1daa6e1ed55a4a936eb4780ecc3fab0f6e7510fcc7f652fc4e23cbc5b4bb42e237833e99f4fd9209853644921c470bfaaa0d847a1fe', false, false, true, false, false, NULL, NULL, NULL, NULL, 0);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `modClassList`, `viewAllGrades`, `userSalt`, `isParent`, `isStudent`, `isTeacher`, `isSchoolAdmin`, `isAdmin`, `studentGPA`, `studentGradeLevel`, `parentAddress`, `parentPhone`, `isPrincipal`) VALUES (4, 'parent@localhost.com', '477347c3a479ec04220f375ce4d6ba2c86f6132d7292688d11d36ac28fdc7300d7c4584f298463fc08d5461cb9262144bff2e48d63a8eec8277fb26c7a0125f4', 'Parent', 'User', 0, 0, '780f4ac261a1243a693dcf7ad9142b42d745e2ce36d102284b0b9e12b13b03e96e36b347620fad9b9fa4a019919653532b2e1c3e1dd9cc524f99080a34efa119', true, false, false, false, false, NULL, NULL, '11574 Test Road, Sault Ste Marie, MI, 49783', '906-635-6677', 0);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `modClassList`, `viewAllGrades`, `userSalt`, `isParent`, `isStudent`, `isTeacher`, `isSchoolAdmin`, `isAdmin`, `studentGPA`, `studentGradeLevel`, `parentAddress`, `parentPhone`, `isPrincipal`) VALUES (5, 'student@localhost.com', '3a3115a8cf336b5906667636ecf5d712b4575b55807efdb036b168735255bb1b49b5066c1813c74bbb7685aa5aec20b3f293fa4078267e40e7b92a6bb928c62c', 'Student', 'User', 0, 0, 'b56067c035fc029daf6b13734df79666d418f35c4e94d8e112a65b1ae07161d30514a1457d17c9a5dbecf346479f31ab13bbdc04b42b88057de2c41050f1e574', false, true, false, false, false, 3.58, 11, NULL, NULL, 0);
INSERT INTO `openEasySIS`.`users` (`userID`, `userEmail`, `userPassword`, `userFirstName`, `userLastName`, `modClassList`, `viewAllGrades`, `userSalt`, `isParent`, `isStudent`, `isTeacher`, `isSchoolAdmin`, `isAdmin`, `studentGPA`, `studentGradeLevel`, `parentAddress`, `parentPhone`, `isPrincipal`) VALUES (6, 'student2@localhost.com', '3a3115a8cf336b5906667636ecf5d712b4575b55807efdb036b168735255bb1b49b5066c1813c74bbb7685aa5aec20b3f293fa4078267e40e7b92a6bb928c62c', 'Student2', 'User', 0, 0, 'b56067c035fc029daf6b13734df79666d418f35c4e94d8e112a65b1ae07161d30514a1457d17c9a5dbecf346479f31ab13bbdc04b42b88057de2c41050f1e574', false, true, false, false, false, 4.00, 12, NULL, NULL, 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`announcements`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`announcements` (`announcementID`, `announcementName`, `announcementDescription`, `announcementPostDate`, `announcementEndDate`) VALUES (1, 'Expired Announcement Test', 'This is a test of an expired announcement made and be displayed', '2016-11-06', '2016-10-10');
INSERT INTO `openEasySIS`.`announcements` (`announcementID`, `announcementName`, `announcementDescription`, `announcementPostDate`, `announcementEndDate`) VALUES (2, 'Non-expired Announcement Test', 'This is a test of an announcement made and be displayed', '2016-11-25', '2017-10-10');
INSERT INTO `openEasySIS`.`announcements` (`announcementID`, `announcementName`, `announcementDescription`, `announcementPostDate`, `announcementEndDate`) VALUES (3, 'non-expired NULL test', 'Test of NULL end', '2016-11-25', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`schoolYear`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`schoolYear` (`schoolYearID`, `fallSemesterStart`, `fallSemesterEnd`, `springSemesterStart`, `springSemesterEnd`, `quarterOneStart`, `quarterOneEnd`, `quarterTwoStart`, `quarterTwoEnd`, `quarterThreeStart`, `quarterThreeEnd`, `quarterFourStart`, `quarterFourEnd`, `schoolYearStart`, `schoolYearEnd`) VALUES (1, '2015-09-01', '2015-12-16', '2016-01-02', '2016-05-01', '2015-09-01', '2015-10-26', '2015-10-27', '2015-12-16', '2016-01-01', '2016-03-31', '2016-04-01', '2016-05-01', '2015-09-01', '2016-05-01');
INSERT INTO `openEasySIS`.`schoolYear` (`schoolYearID`, `fallSemesterStart`, `fallSemesterEnd`, `springSemesterStart`, `springSemesterEnd`, `quarterOneStart`, `quarterOneEnd`, `quarterTwoStart`, `quarterTwoEnd`, `quarterThreeStart`, `quarterThreeEnd`, `quarterFourStart`, `quarterFourEnd`, `schoolYearStart`, `schoolYearEnd`) VALUES (2, '2016-09-01', '2016-12-16', '2017-01-10', '2017-05-21', '2016-09-01', '2016-10-26', '2016-10-27', '2016-12-16', '2017-01-10', '2017-03-31', '2017-04-01', '2017-05-21', '2016-09-01', '2017-05-21');

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`classes`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (1, 11, 'Intro to Business', 3, 2);
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (2, 11, 'Intro to Mathmatics', 3, 2);
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (3, 11, 'CSCI 103', 3, 2);
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (4, 12, 'CSCI 9001', 3, 2);
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (5, 11, 'Math 2015', 3, 1);
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (6, 11, 'English 2015', 3, 1);
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (7, 11, 'Science 2015', 3, 1);
INSERT INTO `openEasySIS`.`classes` (`classID`, `classGrade`, `className`, `classTeacherID`, `schoolYearID`) VALUES (8, 11, 'History 2015', 3, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`materialType`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (1, 'Homework', 1, 40);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (2, 'Quizzes', 1, 10);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (3, 'Exams', 1, 50);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (4, 'Homework', 2, 90);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (5, '2015 Quiz', 5, 80);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (6, '2015 Homework', 5, 10);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (7, '2015 Exam', 5, 10);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (8, 'Math Quizzes', 2, 5);
INSERT INTO `openEasySIS`.`materialType` (`materialTypeID`, `materialName`, `classID`, `materialWeight`) VALUES (9, 'Math Exams', 2, 5);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`materials`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (2, 1, 'Test Quiz', 100, '2017-01-24', 2);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (3, 1, 'Test Exam', 300, '2017-01-24', 3);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (4, 2, '12th Grade Assignment', 900, '2017-02-26', 4);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (5, 5, '5 Exam', 900, '2016-04-01', 5);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (6, 5, '5 Quiz', 500, '2015-11-20', 6);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (7, 5, '5 Homework', 100, '2015-09-11', 7);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (8, 1, 'Q1 Test Homework', 400, '2016-09-24', 1);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (9, 1, 'Q2 Test Exam', 300, '2016-11-05', 3);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (1, 1, 'Q1 Another Homework', 400, '2017-01-04', 1);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (10, 2, 'Math Homework', 20, '2016-09-24', 4);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (11, 2, 'Math Quiz', 40, '2016-11-20', 8);
INSERT INTO `openEasySIS`.`materials` (`materialID`, `materialClassID`, `materialName`, `materialPointsPossible`, `materialDueDate`, `materialTypeID`) VALUES (12, 2, 'Math Exam', 60, '2017-02-03', 9);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`grades`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 1, 1, 400, 1);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 1, 2, 50, 2);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 1, 3, 300, 3);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 5, 5, 800, 4);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 5, 6, 500, 5);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 5, 7, 99, 6);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 1, 8, 300, 7);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 1, 9, 250, 8);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 2, 10, 10, 9);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 2, 11, 40, 10);
INSERT INTO `openEasySIS`.`grades` (`gradeStudentID`, `gradeClassID`, `gradeMaterialID`, `gradeMaterialPointsScored`, `gradeRefID`) VALUES (5, 2, 12, 60, 11);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`studentClassIDs`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (5, 1);
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (5, 2);
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (5, 3);
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (6, 4);
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (5, 5);
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (5, 6);
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (5, 7);
INSERT INTO `openEasySIS`.`studentClassIDs` (`studentID`, `classID`) VALUES (5, 8);

COMMIT;

-- -----------------------------------------------------
-- Data for table `openEasySIS`.`studentParentIDs`
-- -----------------------------------------------------
START TRANSACTION;
USE `openEasySIS`;
INSERT INTO `openEasySIS`.`studentParentIDs` (`studentID`, `parentID`) VALUES (5, 4);
INSERT INTO `openEasySIS`.`studentParentIDs` (`studentID`, `parentID`) VALUES (6, 4);

COMMIT;
