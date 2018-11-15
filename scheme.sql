-- MySQL Script generated by MySQL Workbench
-- Mon Oct  1 18:42:46 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema testing
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema testing
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `testing` DEFAULT CHARACTER SET utf8 ;
USE `testing` ;

-- -----------------------------------------------------
-- Table `testing`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testing`.`questions` (
  `question_id` INT NOT NULL AUTO_INCREMENT,
  `question_complexity` TINYINT(3) ZEROFILL NOT NULL DEFAULT 0,
  PRIMARY KEY (`question_id`),
  UNIQUE INDEX `id_UNIQUE` (`question_id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testing`.`settings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testing`.`settings` (
  `setting_id` INT NOT NULL AUTO_INCREMENT,
  `setting_name` VARCHAR(40) NOT NULL,
  `setting_value` TINYINT(3) UNSIGNED ZEROFILL NOT NULL DEFAULT '000',
  PRIMARY KEY (`setting_id`),
  UNIQUE INDEX `id_UNIQUE` (`setting_id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testing`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testing`.`users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_iq` TINYINT(3) ZEROFILL NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testing`.`questions_stats`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testing`.`questions_stats` (
  `questions_stats_id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `question_used` INT ZEROFILL NOT NULL DEFAULT 0,
  PRIMARY KEY (`questions_stats_id`),
  UNIQUE INDEX `questions_stats_id_UNIQUE` (`questions_stats_id` ASC) VISIBLE,
  INDEX `question_id_idx` (`question_id` ASC) VISIBLE,
  CONSTRAINT `question_id`
    FOREIGN KEY (`question_id`)
    REFERENCES `testing`.`questions` (`question_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `testing`.`testing_stats`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `testing`.`testing_stats` (
  `testing_stat_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `testing_stat_min_complexity` TINYINT(3) ZEROFILL NOT NULL DEFAULT 0,
  `testing_stat_max_complexity` TINYINT(3) ZEROFILL NOT NULL DEFAULT 100,
  `testing_stat_result` TINYINT(3) ZEROFILL NOT NULL DEFAULT 0,
  PRIMARY KEY (`testing_stat_id`),
  UNIQUE INDEX `testing_stat_id_UNIQUE` (`testing_stat_id` ASC) VISIBLE,
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `testing`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
