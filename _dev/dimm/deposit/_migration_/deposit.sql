SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `testDB`;
CREATE DATABASE IF NOT EXISTS `deposit`;
USE `deposit`;
-- --------------------------------------------------------

--
-- Структура таблицы `deposit`
--

CREATE TABLE IF NOT EXISTS `deposit` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `percent` float(6,3) NOT NULL,
  `amount` double NOT NULL,
  `created_at` date DEFAULT NULL,
  `start_at` date NOT NULL,
  `end_at` date NOT NULL,
  `status` smallint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `deposit`
--

INSERT INTO `deposit` (`id`, `user_id`, `percent`, `amount`, `created_at`, `start_at`, `end_at`, `status`) VALUES
(1, 1, 10.000, 457.53125, '1995-01-08', '2016-02-28', '2018-02-28', 1),
(2, 1, 15.000, 4700, '1995-01-08', '2016-01-31', '2018-01-31', 1),
(3, 1, 15.000, 46500, '1995-01-08', '2016-01-08', '2018-01-08', 1),
(4, 2, 10.000, 50, '1995-01-08', '2016-05-30', '2018-05-30', 1),
(5, 2, 20.000, 940, '1995-01-08', '2016-01-08', '2018-01-08', 1),
(6, 2, 12.000, 96000, '1995-01-08', '2016-04-15', '2018-04-15', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `deposit_transaction`
--

CREATE TABLE IF NOT EXISTS `deposit_transaction` (
  `id` int(11) NOT NULL,
  `deposit_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `operation` smallint(2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `id_number` varchar(12) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `birthday` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `id_number`, `firstname`, `lastname`, `gender`, `birthday`) VALUES
(1, '3101305317!', 'Поджог', 'Сараев', '1', '1984-11-28'),
(2, '1111111111', 'Рулон', 'Обоев', '0', '1995-01-08');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Индексы таблицы `deposit_transaction`
--
ALTER TABLE `deposit_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deposit_id` (`deposit_id`),
  ADD KEY `operation` (`operation`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_number` (`id_number`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `deposit_transaction`
--
ALTER TABLE `deposit_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `deposit`
--
ALTER TABLE `deposit`
  ADD CONSTRAINT `deposit_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `deposit_transaction`
--
ALTER TABLE `deposit_transaction`
  ADD CONSTRAINT `deposit_transaction_ibfk_1` FOREIGN KEY (`deposit_id`) REFERENCES `deposit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

