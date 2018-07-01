-- phpMyAdmin SQL Dump
-- version 4.2.13.3
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 01 2018 г., 19:04
-- Версия сервера: 10.0.22-MariaDB
-- Версия PHP: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `php07`
--

-- --------------------------------------------------------

--
-- Структура таблицы `messages_oop`
--

DROP TABLE IF EXISTS `messages_oop`;
CREATE TABLE IF NOT EXISTS `messages_oop` (
`id_msg` int(7) NOT NULL,
  `date_msg` datetime NOT NULL,
  `user` varchar(32) NOT NULL,
  `message` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=551 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `messages_oop`
--

INSERT INTO `messages_oop` (`id_msg`, `date_msg`, `user`, `message`) VALUES
(1, '2016-12-14 00:00:00', 'test', 'Проверка сообщения01'),
(2, '2016-12-14 00:00:00', 'test', 'Проверка сообщения02'),
(3, '2016-12-14 11:13:00', 'test3', 'Проверка сообщения03'),
(4, '2016-12-14 11:13:00', 'test3', 'Проверка сообщения03'),
(5, '2016-12-14 11:13:00', 'test3', 'Проверка сообщения03');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `messages_oop`
--
ALTER TABLE `messages_oop`
 ADD PRIMARY KEY (`id_msg`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `messages_oop`
--
ALTER TABLE `messages_oop`
MODIFY `id_msg` int(7) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=551;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
