-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tempo de Geração: 29/11/2017 às 05:57
-- Versão do servidor: 5.6.36-cll-lve
-- Versão do PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `lanches`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `affiliated`
--

CREATE TABLE IF NOT EXISTS `affiliated` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `affiliated`
--

INSERT INTO `affiliated` (`id`) VALUES
(5),
(9),
(10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `affiliated_laboratory`
--

CREATE TABLE IF NOT EXISTS `affiliated_laboratory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `laboratory_id` int(11) DEFAULT NULL,
  `affiliated_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_380A7A332F2A371E` (`laboratory_id`),
  KEY `IDX_380A7A333AFABA9D` (`affiliated_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Fazendo dump de dados para tabela `affiliated_laboratory`
--

INSERT INTO `affiliated_laboratory` (`id`, `laboratory_id`, `affiliated_id`) VALUES
(2, 1, 5),
(6, 1, 9),
(7, 1, 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `agent`
--

CREATE TABLE IF NOT EXISTS `agent` (
  `id` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `agent_region`
--

CREATE TABLE IF NOT EXISTS `agent_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5185A6503414710B` (`agent_id`),
  KEY `IDX_5185A65098260155` (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BA388B7A76ED395` (`user_id`),
  KEY `IDX_BA388B74584665A` (`product_id`),
  KEY `IDX_BA388B72F2A371E` (`laboratory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Fazendo dump de dados para tabela `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `laboratory_id`, `quantidade`, `price`) VALUES
(5, 10, 6, 1, 1000, '2.50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conteudo_pedido`
--

CREATE TABLE IF NOT EXISTS `conteudo_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_149443EDA76ED395` (`user_id`),
  KEY `IDX_149443ED4854653A` (`pedido_id`),
  KEY `IDX_149443ED4584665A` (`product_id`),
  KEY `IDX_149443ED2F2A371E` (`laboratory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Fazendo dump de dados para tabela `conteudo_pedido`
--

INSERT INTO `conteudo_pedido` (`id`, `user_id`, `pedido_id`, `product_id`, `laboratory_id`, `quantidade`) VALUES
(2, 5, 5, 6, 1, 123),
(3, 5, 8, 6, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cotacoes`
--

CREATE TABLE IF NOT EXISTS `cotacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `archive` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dt_creation` datetime NOT NULL,
  `dt_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C9C73E83A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `distributors`
--

CREATE TABLE IF NOT EXISTS `distributors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `laboratory`
--

CREATE TABLE IF NOT EXISTS `laboratory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_distributor` int(11) DEFAULT NULL,
  `second_distributor` int(11) DEFAULT NULL,
  `third_distributor` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `minimun_value` decimal(10,2) DEFAULT NULL,
  `billing_requirements` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `dt_creation` datetime NOT NULL,
  `dt_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FDC719A83D305AB3` (`first_distributor`),
  KEY `IDX_FDC719A8CA20CB6F` (`second_distributor`),
  KEY `IDX_FDC719A8E3ABBC85` (`third_distributor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `laboratory`
--

INSERT INTO `laboratory` (`id`, `first_distributor`, `second_distributor`, `third_distributor`, `name`, `avatar`, `minimun_value`, `billing_requirements`, `active`, `dt_creation`, `dt_update`) VALUES
(1, NULL, NULL, NULL, 'Lanches', '61194721e541860aaab5f7ae56e1357c.jpeg', '2.50', 'Nada', 1, '2017-11-29 00:54:54', '2017-11-29 12:51:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `laboratory_agent`
--

CREATE TABLE IF NOT EXISTS `laboratory_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `laboratory_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_628A204D2F2A371E` (`laboratory_id`),
  KEY `IDX_628A204D3414710B` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `laboratory_shop`
--

CREATE TABLE IF NOT EXISTS `laboratory_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `laboratory_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A9FBEFCE2F2A371E` (`laboratory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Fazendo dump de dados para tabela `laboratory_shop`
--

INSERT INTO `laboratory_shop` (`id`, `laboratory_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `parcelamento`
--

CREATE TABLE IF NOT EXISTS `parcelamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE IF NOT EXISTS `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `dt_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C4EC16CEA76ED395` (`user_id`),
  KEY `IDX_C4EC16CE2F2A371E` (`laboratory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Fazendo dump de dados para tabela `pedido`
--

INSERT INTO `pedido` (`id`, `user_id`, `laboratory_id`, `name`, `status`, `tipo`, `price`, `dt_creation`) VALUES
(5, 5, 1, '1', 'EM FATURAMENTO', 'boleto', '307.50', '2017-11-29 04:26:41'),
(6, 5, 1, '2', 'EM FATURAMENTO', 'boleto', '0.00', '2017-11-29 04:27:49'),
(7, 5, 1, '3', 'EM FATURAMENTO', 'boleto', '0.00', '2017-11-29 04:31:32'),
(8, 5, 1, '4', 'EM FATURAMENTO', 'boleto', '5.00', '2017-11-29 04:34:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `dt_creation` datetime NOT NULL,
  `dt_update` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Fazendo dump de dados para tabela `product`
--

INSERT INTO `product` (`id`, `name`, `photo`, `active`, `dt_creation`, `dt_update`) VALUES
(6, 'Lanche 1', 'a439c0477ce244fb6a63b8c46c4568ab.jpeg', 1, '2017-11-29 04:26:17', '2017-11-29 04:26:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_laboratory`
--

CREATE TABLE IF NOT EXISTS `product_laboratory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `factory_value` decimal(10,2) DEFAULT NULL,
  `cost_value` decimal(10,2) DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6EA046834584665A` (`product_id`),
  KEY `IDX_6EA046832F2A371E` (`laboratory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Fazendo dump de dados para tabela `product_laboratory`
--

INSERT INTO `product_laboratory` (`id`, `product_id`, `laboratory_id`, `factory_value`, `cost_value`, `discount_value`, `quantidade`) VALUES
(6, 6, 1, '2.50', '2.50', '0.00', 998875);

-- --------------------------------------------------------

--
-- Estrutura para tabela `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F62F1765D83CC1` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `shoplaboratory`
--

CREATE TABLE IF NOT EXISTS `shoplaboratory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `laboratory_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A9EC363E2F2A371E` (`laboratory_id`),
  KEY `IDX_A9EC363EA76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Fazendo dump de dados para tabela `shoplaboratory`
--

INSERT INTO `shoplaboratory` (`id`, `laboratory_id`, `user_id`, `price`) VALUES
(5, 1, 10, '2500.00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `acronym` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `celphone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dt_creation` datetime DEFAULT NULL,
  `dt_update` datetime DEFAULT NULL,
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Fazendo dump de dados para tabela `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `first_name`, `last_name`, `celphone`, `phone`, `dt_creation`, `dt_update`, `discr`) VALUES
(3, 'admin', 'admin', 'admin', 'admin', 1, 'htckqtokeygwos8ow84gsws404wskcc', '9JB3+eKMUWY275PRq47UTXrIjJYqsL0T51XAWk1Q92DSG8RwbQpNuLPg8yXuJ9QsL4qzxo0hu75KGacXNdRpzw==', '2017-11-29 12:15:23', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 0, NULL, NULL, NULL, NULL, NULL, '2017-11-29 01:13:35', '2017-11-29 12:15:23', 'user'),
(5, 'daniel@verticis.com', 'daniel@verticis.com', 'daniel@verticis.com', 'daniel@verticis.com', 1, 'jh8ydqg1lq0wk00ssco8k4ksog484ck', 'mdXCMOiFtt7ntixp5ihz314fyFXAMSWVVLlS74M7TrOOOCh292ajO9huzQ0ZjB8rsM1R08rHPdiZU7Lq0NqW4w==', '2017-11-29 11:57:04', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'Daniel', NULL, '(31) 97173-5273', NULL, '2017-11-29 04:25:40', '2017-11-29 11:57:04', 'affiliated'),
(9, 'leandrofarias269@gmail.com', 'leandrofarias269@gmail.com', 'leandrofarias269@gmail.com', 'leandrofarias269@gmail.com', 1, 'az6pw9c0zj4k8oc0c44k8c80gwkwccg', '2g7FOZuSebuqBEFvVqvoRF/RnV59gIPUMlFp9hr+iRFYyBbjlb+z18dh/vQIsf8bmrlBB5M92QAgkbQ2dZTh4Q==', '2017-11-29 08:27:11', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'LEANDRO FARIAS SANTANA', NULL, '(31) 98545-9337', '(31) 9854-59337', '2017-11-29 08:26:56', '2017-11-29 08:27:11', 'affiliated'),
(10, 'Sistemalixo@daniellixo.wellerson', 'sistemalixo@daniellixo.wellerson', 'Sistemalixo@daniellixo.wellerson', 'sistemalixo@daniellixo.wellerson', 1, 'kdif7qjqq5wc4kwcs8g0gc80kwkcokk', 'VHBuNXP0lavakcDyVfhsCmtSJMftQBFpW2AvNFe/29r0x93HBqQwKXPj9CKPxGAcjO5iUyDVxO/Qm84hGILuxw==', '2017-11-29 12:52:06', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'well', NULL, '(31) 99999-9999', '(31) 99999-9999', '2017-11-29 12:51:48', '2017-11-29 12:52:43', 'affiliated');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_shop`
--

CREATE TABLE IF NOT EXISTS `user_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D6EB006BA76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Fazendo dump de dados para tabela `user_shop`
--

INSERT INTO `user_shop` (`id`, `user_id`) VALUES
(2, 5);

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `affiliated`
--
ALTER TABLE `affiliated`
  ADD CONSTRAINT `FK_935FEF48BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `affiliated_laboratory`
--
ALTER TABLE `affiliated_laboratory`
  ADD CONSTRAINT `FK_380A7A332F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_380A7A333AFABA9D` FOREIGN KEY (`affiliated_id`) REFERENCES `affiliated` (`id`);

--
-- Restrições para tabelas `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `FK_268B9C9DBF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `agent_region`
--
ALTER TABLE `agent_region`
  ADD CONSTRAINT `FK_5185A6503414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_5185A65098260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Restrições para tabelas `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_BA388B72F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_BA388B74584665A` FOREIGN KEY (`product_id`) REFERENCES `product_laboratory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_BA388B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `conteudo_pedido`
--
ALTER TABLE `conteudo_pedido`
  ADD CONSTRAINT `FK_149443ED2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_149443ED4584665A` FOREIGN KEY (`product_id`) REFERENCES `product_laboratory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_149443ED4854653A` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `FK_149443EDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `cotacoes`
--
ALTER TABLE `cotacoes`
  ADD CONSTRAINT `FK_C9C73E83A76ED395` FOREIGN KEY (`user_id`) REFERENCES `affiliated` (`id`);

--
-- Restrições para tabelas `laboratory`
--
ALTER TABLE `laboratory`
  ADD CONSTRAINT `FK_FDC719A83D305AB3` FOREIGN KEY (`first_distributor`) REFERENCES `distributors` (`id`),
  ADD CONSTRAINT `FK_FDC719A8CA20CB6F` FOREIGN KEY (`second_distributor`) REFERENCES `distributors` (`id`),
  ADD CONSTRAINT `FK_FDC719A8E3ABBC85` FOREIGN KEY (`third_distributor`) REFERENCES `distributors` (`id`);

--
-- Restrições para tabelas `laboratory_agent`
--
ALTER TABLE `laboratory_agent`
  ADD CONSTRAINT `FK_628A204D2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_628A204D3414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`);

--
-- Restrições para tabelas `laboratory_shop`
--
ALTER TABLE `laboratory_shop`
  ADD CONSTRAINT `FK_A9FBEFCE2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`);

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `FK_C4EC16CE2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_C4EC16CEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `affiliated` (`id`);

--
-- Restrições para tabelas `product_laboratory`
--
ALTER TABLE `product_laboratory`
  ADD CONSTRAINT `FK_6EA046832F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_6EA046834584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Restrições para tabelas `region`
--
ALTER TABLE `region`
  ADD CONSTRAINT `FK_F62F1765D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`);

--
-- Restrições para tabelas `shoplaboratory`
--
ALTER TABLE `shoplaboratory`
  ADD CONSTRAINT `FK_A9EC363E2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_A9EC363EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `user_shop`
--
ALTER TABLE `user_shop`
  ADD CONSTRAINT `FK_D6EB006BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `affiliated` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
