CREATE TABLE `equipamentos` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `modelo` varchar(100),
  `serial` varchar(20),
  `estado` varchar(50),
  `location_vector` varchar(20),
  `location_wing` varchar(10),
  `location_floor` int,
  `location_room` int,
  `data_aquisicao` date,
  `tipo_aquisicao` varchar(50),
  `custo_aquisicao` decimal(10,2),
  `ano_fabrico` year,
  `grupo` varchar(100),
  `departamento` varchar(100),
  `criticidade` varchar(20),
  `imagem` varchar(255),
  `marca` varchar(100),
  `last_scanned` timestamp,
  `deleted_at` datetime,
  `observacao` text
);

CREATE TABLE `fornecedores` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome_empresa` varchar(150) NOT NULL,
  `nif` varchar(20) UNIQUE NOT NULL,
  `telefone` varchar(30),
  `email` varchar(100),
  `endereco` varchar(255),
  `website` varchar(255),
  `pessoa_contacto` varchar(100),
  `telefone_contacto` varchar(30),
  `tipo_fornecedor` enum,
  `deleted_at` datetime
);

CREATE TABLE `equipamento_fornecedor` (
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `data_associacao` timestamp
);

CREATE TABLE `documentos_equipamento` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int,
  `tipo_documento` varchar(50) NOT NULL,
  `tipo_contrato` varchar(50),
  `periodicidade` varchar(50),
  `nome_ficheiro` varchar(255) NOT NULL,
  `caminho_ficheiro` varchar(255) NOT NULL,
  `data_upload` timestamp,
  `data_fim_garantia` date,
  `data_inicio_garantia` date,
  `data_validade` date
);

CREATE TABLE `landing_settings` (
  `id` int PRIMARY KEY DEFAULT 1,
  `hero_title` varchar(255),
  `hero_subtitle` text,
  `about_title` varchar(255),
  `about_text` text
);

CREATE TABLE `landing_team` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(100),
  `cargo` varchar(100),
  `descricao` text,
  `imagem` varchar(255),
  `github` varchar(255)
);

CREATE TABLE `landing_services` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100),
  `descricao` text
);

CREATE TABLE `landing_faq` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pergunta` text,
  `resposta` text
);

CREATE TABLE `staff` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `role` varchar(100),
  `departamento` varchar(100),
  `disponibilidade` varchar(50),
  `contacto` varchar(20),
  `staff_id` varchar(20),
  `imagem` varchar(255),
  `deleted_at` timestamp
);

CREATE TABLE `pager_codes` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `codigo_pager` varchar(20) UNIQUE NOT NULL,
  `funcao_hospitalar` enum NOT NULL,
  `descricao` text,
  `data_criacao` timestamp
);

CREATE TABLE `utilizadores` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum NOT NULL,
  `criado_em` timestamp
);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

ALTER TABLE `documentos_equipamento` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `documentos_equipamento` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);
