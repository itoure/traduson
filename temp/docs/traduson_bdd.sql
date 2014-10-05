SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `traduson_dev` ;
CREATE SCHEMA IF NOT EXISTS `traduson_dev` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `traduson_dev` ;

-- -----------------------------------------------------
-- Table `traduson_dev`.`GEN_GENRE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `traduson_dev`.`GEN_GENRE` ;

CREATE  TABLE IF NOT EXISTS `traduson_dev`.`GEN_GENRE` (
  `gen_id` INT NOT NULL AUTO_INCREMENT ,
  `gen_label` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`gen_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `traduson_dev`.`USR_USER`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `traduson_dev`.`USR_USER` ;

CREATE  TABLE IF NOT EXISTS `traduson_dev`.`USR_USER` (
  `usr_id` INT NOT NULL AUTO_INCREMENT ,
  `usr_login` VARCHAR(45) NOT NULL ,
  `usr_email` VARCHAR(45) NOT NULL ,
  `usr_password` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`usr_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `traduson_dev`.`ART_ARTISTE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `traduson_dev`.`ART_ARTISTE` ;

CREATE  TABLE IF NOT EXISTS `traduson_dev`.`ART_ARTISTE` (
  `art_id` INT NOT NULL AUTO_INCREMENT ,
  `art_name` VARCHAR(45) NOT NULL ,
  `art_url` VARCHAR(145) NULL ,
  PRIMARY KEY (`art_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `traduson_dev`.`LYR_LYRICS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `traduson_dev`.`LYR_LYRICS` ;

CREATE  TABLE IF NOT EXISTS `traduson_dev`.`LYR_LYRICS` (
  `lyr_id` INT NOT NULL AUTO_INCREMENT ,
  `lyr_title` VARCHAR(100) NOT NULL ,
  `lyr_create_date` DATETIME NOT NULL ,
  `lyr_nb_comment` INT NULL DEFAULT 0 ,
  `lyr_feat` VARCHAR(100) NULL ,
  `lyr_youtube` VARCHAR(45) NULL ,
  `lyr_image` VARCHAR(145) NULL ,
  `gen_id` INT NOT NULL ,
  `usr_id` INT NOT NULL ,
  `art_id` INT NOT NULL ,
  `lyr_url` VARCHAR(145) NULL ,
  `lyr_block` TINYINT NOT NULL DEFAULT 0 ,
  `lyr_taux` INT NULL DEFAULT 0 ,
  `lyr_year` INT NULL ,
  `lyr_producer` VARCHAR(100) NULL ,
  `lyr_album` VARCHAR(100) NULL ,
  `lyr_up` INT NULL DEFAULT 0 ,
  `lyr_down` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`lyr_id`) ,
  INDEX `fk_LYR_LYRICS_GEN_GENRE_idx` (`gen_id` ASC) ,
  INDEX `fk_LYR_LYRICS_USR_USER1_idx` (`usr_id` ASC) ,
  INDEX `fk_LYR_LYRICS_ART_ARTISTE1_idx` (`art_id` ASC) ,
  CONSTRAINT `fk_LYR_LYRICS_GEN_GENRE`
    FOREIGN KEY (`gen_id` )
    REFERENCES `traduson_dev`.`GEN_GENRE` (`gen_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_LYR_LYRICS_USR_USER1`
    FOREIGN KEY (`usr_id` )
    REFERENCES `traduson_dev`.`USR_USER` (`usr_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_LYR_LYRICS_ART_ARTISTE1`
    FOREIGN KEY (`art_id` )
    REFERENCES `traduson_dev`.`ART_ARTISTE` (`art_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `traduson_dev`.`TRA_TRANSLATE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `traduson_dev`.`TRA_TRANSLATE` ;

CREATE  TABLE IF NOT EXISTS `traduson_dev`.`TRA_TRANSLATE` (
  `tra_id` INT NOT NULL AUTO_INCREMENT ,
  `tra_line` VARCHAR(245) NOT NULL ,
  `tra_translate` VARCHAR(245) NULL ,
  `lyr_id` INT NOT NULL ,
  PRIMARY KEY (`tra_id`) ,
  INDEX `fk_TRA_TRANSLATE_LYR_LYRICS1_idx` (`lyr_id` ASC) ,
  CONSTRAINT `fk_TRA_TRANSLATE_LYR_LYRICS1`
    FOREIGN KEY (`lyr_id` )
    REFERENCES `traduson_dev`.`LYR_LYRICS` (`lyr_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

USE `traduson_dev` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `traduson_dev`.`GEN_GENRE`
-- -----------------------------------------------------
START TRANSACTION;
USE `traduson_dev`;
INSERT INTO `traduson_dev`.`GEN_GENRE` (`gen_id`, `gen_label`) VALUES (NULL, 'Rap');
INSERT INTO `traduson_dev`.`GEN_GENRE` (`gen_id`, `gen_label`) VALUES (NULL, 'R&B');
INSERT INTO `traduson_dev`.`GEN_GENRE` (`gen_id`, `gen_label`) VALUES (NULL, 'Pop');
INSERT INTO `traduson_dev`.`GEN_GENRE` (`gen_id`, `gen_label`) VALUES (NULL, 'Rock');

COMMIT;
