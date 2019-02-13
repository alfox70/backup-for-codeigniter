-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--

CREATE TABLE `backup` (
  `backup_id` int(10) UNSIGNED NOT NULL,
  `backup_name` varchar(255) NOT NULL,
  `backup_location` varchar(255) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `backup`
  ADD PRIMARY KEY (`backup_id`),
  ADD UNIQUE KEY `backup_name_UNIQUE` (`backup_name`);

