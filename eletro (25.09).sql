-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/09/2026 às 16:46
-- Versão do servidor: 8.0.46
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `eletro`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `as_built`
--

CREATE TABLE `as_built` (
  `n_as_built` int NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `observacoes` text,
  `data_fiscalizacao` date DEFAULT NULL,
  `fk_obra_id_obra` int DEFAULT NULL,
  `fk_status_id_status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `as_built`
--

INSERT INTO `as_built` (`n_as_built`, `titulo`, `responsavel`, `observacoes`, `data_fiscalizacao`, `fk_obra_id_obra`, `fk_status_id_status`) VALUES
(7, 'As Built Teste RD360', 'Amanda Campos', 'Testando alterações.', '2026-09-22', 10, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `equipamentos`
--

CREATE TABLE `equipamentos` (
  `id_equipamento` int NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `potencia` varchar(30) DEFAULT NULL,
  `placa` varchar(100) DEFAULT NULL,
  `cia` varchar(100) DEFAULT NULL,
  `fk_poste_geolocalizacao_nota` varchar(50) DEFAULT NULL,
  `fk_poste_geolocalizacao_id_geolocalizacao` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `equipamentos`
--

INSERT INTO `equipamentos` (`id_equipamento`, `tipo`, `potencia`, `placa`, `cia`, `fk_poste_geolocalizacao_nota`, `fk_poste_geolocalizacao_id_geolocalizacao`) VALUES
(6, '', '', 'J02475', '456987', 'teste-00000', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estrutura_primaria`
--

CREATE TABLE `estrutura_primaria` (
  `id_estrutura_primaria` int NOT NULL,
  `tipo_estrutura` varchar(100) DEFAULT NULL,
  `fk_pontos_coletados_id_ponto` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estrutura_secundaria`
--

CREATE TABLE `estrutura_secundaria` (
  `id_estrutura_secundaria` int NOT NULL,
  `tipo_estrutura` varchar(100) DEFAULT NULL,
  `fk_pontos_coletados_id_ponto` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos`
--

CREATE TABLE `fotos` (
  `id_foto` int NOT NULL,
  `n_ponto` int DEFAULT NULL,
  `caminho_imagem` varchar(255) DEFAULT NULL,
  `data_foto` date DEFAULT NULL,
  `fk_as_built_n_as_built` int DEFAULT NULL,
  `fk_pontos_coletados_id_ponto` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `fotos`
--

INSERT INTO `fotos` (`id_foto`, `n_ponto`, `caminho_imagem`, `data_foto`, `fk_as_built_n_as_built`, `fk_pontos_coletados_id_ponto`) VALUES
(1, NULL, 'uploads/rd360_6ab2975f851058.91265586.jpg', '2026-09-22', 7, NULL),
(2, 1, 'uploads/rd360_6ab2c3a150c5c.jpeg', '2026-09-22', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `municipio`
--

CREATE TABLE `municipio` (
  `id_municipio` int NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `municipio`
--

INSERT INTO `municipio` (`id_municipio`, `nome`, `estado`) VALUES
(1, 'Feira de Santana', 'BA'),
(2, 'varzea do poço', 'BA'),
(3, 'JACOBINA', 'BA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `obra`
--

CREATE TABLE `obra` (
  `id_obra` int NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `nota` varchar(50) DEFAULT NULL,
  `projeto_obra` varchar(100) DEFAULT NULL,
  `id_investimento` varchar(50) DEFAULT NULL,
  `qtd_postes` int DEFAULT NULL,
  `uc_previsto` int DEFAULT NULL,
  `data_energizacao` date DEFAULT NULL,
  `fk_usuario_id_usuario` int DEFAULT NULL,
  `fk_municipio_id_municipio` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `obra`
--

INSERT INTO `obra` (`id_obra`, `titulo`, `nota`, `projeto_obra`, `id_investimento`, `qtd_postes`, `uc_previsto`, `data_energizacao`, `fk_usuario_id_usuario`, `fk_municipio_id_municipio`) VALUES
(10, 'Teste-alterações', '23567849', 'B-0098213', '12', 6, 18, '2026-09-09', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pontos_coletados`
--

CREATE TABLE `pontos_coletados` (
  `id_ponto` int NOT NULL,
  `nota_obra` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `fk_as_built_n_as_built` int DEFAULT NULL,
  `fk_poste_geolocalizacao_nota` varchar(50) DEFAULT NULL,
  `fk_poste_geolocalizacao_id_geolocalizacao` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `pontos_coletados`
--

INSERT INTO `pontos_coletados` (`id_ponto`, `nota_obra`, `latitude`, `longitude`, `fk_as_built_n_as_built`, `fk_poste_geolocalizacao_nota`, `fk_poste_geolocalizacao_id_geolocalizacao`) VALUES
(1, '23567849', -11.18236048, -40.53705910, 7, 'teste-00000', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `poste_geolocalizacao`
--

CREATE TABLE `poste_geolocalizacao` (
  `nota` varchar(50) NOT NULL,
  `id_geolocalizacao` int NOT NULL,
  `tipo_poste` varchar(50) DEFAULT NULL,
  `material_poste` varchar(30) DEFAULT NULL,
  `altura_poste` varchar(20) DEFAULT NULL,
  `esforco_poste` varchar(20) DEFAULT NULL,
  `coordenada_latitude` decimal(10,8) DEFAULT NULL,
  `cordenada_longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `fk_obra_id_obra` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `poste_geolocalizacao`
--

INSERT INTO `poste_geolocalizacao` (`nota`, `id_geolocalizacao`, `tipo_poste`, `material_poste`, `altura_poste`, `esforco_poste`, `coordenada_latitude`, `cordenada_longitude`, `latitude`, `longitude`, `fk_obra_id_obra`) VALUES
('teste-00000', 1, 'Duplo T', 'Fibra de vidro', '5m', '123', -11.18236048, -40.53705910, -11.18236048, -40.53705910, 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `status`
--

CREATE TABLE `status` (
  `id_status` int NOT NULL,
  `nome_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `status`
--

INSERT INTO `status` (`id_status`, `nome_status`) VALUES
(1, 'Pendente'),
(2, 'Em andamento'),
(3, 'Concluída');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `tipo_usuario` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo_usuario`) VALUES
(1, 'Amanda Campos', 'amanda@gmail.com', '$2y$10$xydXqxAiHSP8rXU8guVB9.J.6HESc32iU8Rr4RLUvNhGICOf2WjtO', 'Técnico');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vao`
--

CREATE TABLE `vao` (
  `id_vao` int NOT NULL,
  `distancia` varchar(30) DEFAULT NULL,
  `tipo_condutor` varchar(100) DEFAULT NULL,
  `fk_pontos_coletados_id_ponto_origem` int DEFAULT NULL,
  `fk_pontos_coletados_id_ponto_destino` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `as_built`
--
ALTER TABLE `as_built`
  ADD PRIMARY KEY (`n_as_built`),
  ADD KEY `fk_obra_id_obra` (`fk_obra_id_obra`),
  ADD KEY `fk_status_id_status` (`fk_status_id_status`);

--
-- Índices de tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  ADD PRIMARY KEY (`id_equipamento`),
  ADD KEY `fk_poste_geolocalizacao_nota` (`fk_poste_geolocalizacao_nota`,`fk_poste_geolocalizacao_id_geolocalizacao`);

--
-- Índices de tabela `estrutura_primaria`
--
ALTER TABLE `estrutura_primaria`
  ADD PRIMARY KEY (`id_estrutura_primaria`),
  ADD KEY `fk_pontos_coletados_id_ponto` (`fk_pontos_coletados_id_ponto`);

--
-- Índices de tabela `estrutura_secundaria`
--
ALTER TABLE `estrutura_secundaria`
  ADD PRIMARY KEY (`id_estrutura_secundaria`),
  ADD KEY `fk_pontos_coletados_id_ponto` (`fk_pontos_coletados_id_ponto`);

--
-- Índices de tabela `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `fk_as_built_n_as_built` (`fk_as_built_n_as_built`),
  ADD KEY `fk_fotos_ponto` (`fk_pontos_coletados_id_ponto`);

--
-- Índices de tabela `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id_municipio`);

--
-- Índices de tabela `obra`
--
ALTER TABLE `obra`
  ADD PRIMARY KEY (`id_obra`),
  ADD KEY `fk_usuario_id_usuario` (`fk_usuario_id_usuario`),
  ADD KEY `fk_municipio_id_municipio` (`fk_municipio_id_municipio`);

--
-- Índices de tabela `pontos_coletados`
--
ALTER TABLE `pontos_coletados`
  ADD PRIMARY KEY (`id_ponto`),
  ADD KEY `fk_as_built_n_as_built` (`fk_as_built_n_as_built`),
  ADD KEY `fk_poste_geolocalizacao_nota` (`fk_poste_geolocalizacao_nota`,`fk_poste_geolocalizacao_id_geolocalizacao`);

--
-- Índices de tabela `poste_geolocalizacao`
--
ALTER TABLE `poste_geolocalizacao`
  ADD PRIMARY KEY (`nota`,`id_geolocalizacao`),
  ADD KEY `fk_obra_id_obra` (`fk_obra_id_obra`);

--
-- Índices de tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices de tabela `vao`
--
ALTER TABLE `vao`
  ADD PRIMARY KEY (`id_vao`),
  ADD KEY `fk_pontos_coletados_id_ponto_origem` (`fk_pontos_coletados_id_ponto_origem`),
  ADD KEY `fk_pontos_coletados_id_ponto_destino` (`fk_pontos_coletados_id_ponto_destino`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `as_built`
--
ALTER TABLE `as_built`
  MODIFY `n_as_built` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  MODIFY `id_equipamento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `estrutura_primaria`
--
ALTER TABLE `estrutura_primaria`
  MODIFY `id_estrutura_primaria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `estrutura_secundaria`
--
ALTER TABLE `estrutura_secundaria`
  MODIFY `id_estrutura_secundaria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id_foto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id_municipio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `obra`
--
ALTER TABLE `obra`
  MODIFY `id_obra` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `pontos_coletados`
--
ALTER TABLE `pontos_coletados`
  MODIFY `id_ponto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `vao`
--
ALTER TABLE `vao`
  MODIFY `id_vao` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `as_built`
--
ALTER TABLE `as_built`
  ADD CONSTRAINT `as_built_ibfk_1` FOREIGN KEY (`fk_obra_id_obra`) REFERENCES `obra` (`id_obra`),
  ADD CONSTRAINT `as_built_ibfk_2` FOREIGN KEY (`fk_status_id_status`) REFERENCES `status` (`id_status`);

--
-- Restrições para tabelas `equipamentos`
--
ALTER TABLE `equipamentos`
  ADD CONSTRAINT `equipamentos_ibfk_1` FOREIGN KEY (`fk_poste_geolocalizacao_nota`,`fk_poste_geolocalizacao_id_geolocalizacao`) REFERENCES `poste_geolocalizacao` (`nota`, `id_geolocalizacao`);

--
-- Restrições para tabelas `estrutura_primaria`
--
ALTER TABLE `estrutura_primaria`
  ADD CONSTRAINT `estrutura_primaria_ibfk_1` FOREIGN KEY (`fk_pontos_coletados_id_ponto`) REFERENCES `pontos_coletados` (`id_ponto`);

--
-- Restrições para tabelas `estrutura_secundaria`
--
ALTER TABLE `estrutura_secundaria`
  ADD CONSTRAINT `estrutura_secundaria_ibfk_1` FOREIGN KEY (`fk_pontos_coletados_id_ponto`) REFERENCES `pontos_coletados` (`id_ponto`);

--
-- Restrições para tabelas `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`fk_as_built_n_as_built`) REFERENCES `as_built` (`n_as_built`),
  ADD CONSTRAINT `fotos_ibfk_2` FOREIGN KEY (`fk_pontos_coletados_id_ponto`) REFERENCES `pontos_coletados` (`id_ponto`);

--
-- Restrições para tabelas `obra`
--
ALTER TABLE `obra`
  ADD CONSTRAINT `obra_ibfk_1` FOREIGN KEY (`fk_usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `obra_ibfk_2` FOREIGN KEY (`fk_municipio_id_municipio`) REFERENCES `municipio` (`id_municipio`);

--
-- Restrições para tabelas `pontos_coletados`
--
ALTER TABLE `pontos_coletados`
  ADD CONSTRAINT `pontos_coletados_ibfk_1` FOREIGN KEY (`fk_as_built_n_as_built`) REFERENCES `as_built` (`n_as_built`),
  ADD CONSTRAINT `pontos_coletados_ibfk_2` FOREIGN KEY (`fk_poste_geolocalizacao_nota`,`fk_poste_geolocalizacao_id_geolocalizacao`) REFERENCES `poste_geolocalizacao` (`nota`, `id_geolocalizacao`);

--
-- Restrições para tabelas `poste_geolocalizacao`
--
ALTER TABLE `poste_geolocalizacao`
  ADD CONSTRAINT `poste_geolocalizacao_ibfk_1` FOREIGN KEY (`fk_obra_id_obra`) REFERENCES `obra` (`id_obra`);

--
-- Restrições para tabelas `vao`
--
ALTER TABLE `vao`
  ADD CONSTRAINT `vao_ibfk_1` FOREIGN KEY (`fk_pontos_coletados_id_ponto_origem`) REFERENCES `pontos_coletados` (`id_ponto`),
  ADD CONSTRAINT `vao_ibfk_2` FOREIGN KEY (`fk_pontos_coletados_id_ponto_destino`) REFERENCES `pontos_coletados` (`id_ponto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
