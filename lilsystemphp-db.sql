-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07-Mar-2023 às 23:06
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lilsystemphp-db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `messages`
--

INSERT INTO `messages` (`id`, `name`, `message`, `date`) VALUES
(1, 'Lorem Ipsum 1', 'Ut tellus urna, aliquet vel diam id, lobortis aliquet mauris.', '2023-03-07 21:50:01'),
(2, 'Lorem Ipsum 2', 'Quisque nec arcu eget odio vulputate accumsan non eget elit.', '2023-03-07 21:50:01'),
(3, 'Lorem Ipsum 3', 'Praesent eu sollicitudin mauris. Quisque mattis semper lectus at pretium.', '2023-03-07 21:50:01'),
(4, 'Lorem Ipsum 4', 'Aliquam egestas sit amet mauris at porttitor. Integer nisi dui.', '2023-03-07 21:50:01'),
(5, 'Lorem Ipsum 5', 'Ut ante dui, varius ut finibus eget, dictum et eros.', '2023-03-07 21:50:01'),
(6, 'Lorem Ipsum 6', 'Vestibulum consequat, risus ut porta posuere, neque justo commodo mauris.', '2023-03-07 21:50:01'),
(7, 'Lorem Ipsum 7', 'Donec vehicula nunc felis, et scelerisque enim sollicitudin eget. Donec.', '2023-03-07 21:50:01'),
(8, 'Lorem Ipsum 8', 'Fusce lectus leo, auctor sed feugiat molestie, bibendum vitae urna.', '2023-03-07 21:50:01'),
(9, 'Lorem Ipsum 9', 'Nunc condimentum, lorem egestas auctor dapibus, risus ante aliquam lacus.', '2023-03-07 21:50:01'),
(10, 'Lorem Ipsum 10', 'Fusce et ligula sed ipsum euismod mattis. Pellentesque sit amet.', '2023-03-07 21:50:01'),
(11, 'Lorem Ipsum 11', 'Nam aliquet rhoncus mi, vel tempor nisl iaculis sit amet.', '2023-03-07 21:50:01'),
(12, 'Lorem Ipsum 12', 'Curabitur porttitor arcu sagittis ligula vestibulum, a egestas turpis sagittis.', '2023-03-07 21:50:01'),
(13, 'Lorem Ipsum 13', 'Morbi venenatis consectetur enim, eget gravida nisl tincidunt non. Nam.', '2023-03-07 21:50:01'),
(14, 'Lorem Ipsum 14', 'Mauris ligula leo, maximus id nisi sed, rutrum hendrerit leo.', '2023-03-07 21:50:01'),
(15, 'Lorem Ipsum 15', 'Fusce iaculis interdum mi eu rhoncus. In sit amet sem.', '2023-03-07 21:50:01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'admin@admin.com', '$2y$10$tjaC6ylVny0SwFhPLATKd.I0pMW3dgUyGZ0i9Tl8kxqgafcmKuFw.');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
