-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 01-Nov-2017 às 15:22
-- Versão do servidor: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `acompanhamentos`
--

CREATE TABLE `acompanhamentos` (
  `id_acomp` int(11) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `acompanhamentos`
--

INSERT INTO `acompanhamentos` (`id_acomp`, `cpf`, `email`) VALUES
(8, '177.177.800-07', 'vini_willers@hotmail.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cuidados`
--

CREATE TABLE `cuidados` (
  `id_cuid` int(11) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `temperatura` varchar(4) NOT NULL,
  `pressao` text,
  `medicam` varchar(50) DEFAULT NULL,
  `recom` varchar(120) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `cuidados`
--

INSERT INTO `cuidados` (`id_cuid`, `cpf`, `temperatura`, `pressao`, `medicam`, `recom`, `created`, `uid`) VALUES
(34, '533.279.000-78', '36,5', '', '', '', '2017-10-16 20:37:53', 201),
(130, '000.000.001-91', '36,5', '12 por 8', '', '', '2017-10-21 00:11:04', 199),
(163, '177.177.800-07', '32', '', '', '', '2017-10-27 15:35:24', 201),
(182, '177.177.800-07', '36,5', '', '', '', '2017-10-27 19:48:09', 201),
(191, '177.177.800-07', '36,5', '', '', '', '2017-10-27 22:01:10', 199),
(192, '177.177.800-07', '36,5', '', '', '', '2017-10-27 22:06:24', 199);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `idade` date NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pacientes`
--

INSERT INTO `pacientes` (`id`, `nome`, `idade`, `cpf`, `created`, `uid`) VALUES
(17, 'Silvia', '1965-08-07', '533.279.000-78', '2017-10-16 20:15:50', 201),
(50, 'Silvia Marschner', '1965-08-07', '000.000.001-91', '2017-10-21 00:10:46', 199),
(51, 'Vinicius', '1993-03-05', '177.177.800-07', '2017-10-24 00:52:59', 199);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(200) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`uid`, `name`, `email`, `phone`, `password`, `address`, `city`, `created`) VALUES
(199, 'Vinicius', 'vini_willers@hotmail.com', '5199883110', '$2a$10$c8902f983e9c9bb0ab10buo2mbq9FlWcdh8FABef30K8T3BBb2duO', 'Avenida Joao Correa', '', '2017-09-13 23:52:21'),
(200, 'Vini2', 'vini_willers2@hotmail.com', '5199877777777', '$2a$10$24bbbd67c01834e7356dauPTQkmu6zgmqqJ5kQXLSmaBlbesfitgW', 'Rua rui,m', '', '2017-10-06 00:24:39'),
(201, 'Vini2', 'vini_willers@hotmail.com2', '2132131231231', '$2a$10$c0c3939e2c4a19df08201ezco3SwLwLSI72RTwr2B4cxM5XZ1DAfS', 'asddasdsa', '', '2017-10-16 20:01:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acompanhamentos`
--
ALTER TABLE `acompanhamentos`
  ADD PRIMARY KEY (`id_acomp`),
  ADD KEY `fk_cpf` (`cpf`),
  ADD KEY `fk_email` (`email`);

--
-- Indexes for table `cuidados`
--
ALTER TABLE `cuidados`
  ADD PRIMARY KEY (`id_cuid`),
  ADD KEY `fk_cpf_1` (`cpf`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acompanhamentos`
--
ALTER TABLE `acompanhamentos`
  MODIFY `id_acomp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `cuidados`
--
ALTER TABLE `cuidados`
  MODIFY `id_cuid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;
--
-- AUTO_INCREMENT for table `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `acompanhamentos`
--
ALTER TABLE `acompanhamentos`
  ADD CONSTRAINT `fk_cpf` FOREIGN KEY (`cpf`) REFERENCES `pacientes` (`cpf`),
  ADD CONSTRAINT `fk_email` FOREIGN KEY (`email`) REFERENCES `usuarios` (`email`);

--
-- Limitadores para a tabela `cuidados`
--
ALTER TABLE `cuidados`
  ADD CONSTRAINT `fk_cpf_1` FOREIGN KEY (`cpf`) REFERENCES `pacientes` (`cpf`),
  ADD CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `usuarios` (`uid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
