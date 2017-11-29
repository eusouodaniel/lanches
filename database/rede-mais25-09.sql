-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25-Set-2017 às 15:27
-- Versão do servidor: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rede-mais`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `affiliated`
--

CREATE TABLE `affiliated` (
  `id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `document` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `affiliated_laboratory`
--

CREATE TABLE `affiliated_laboratory` (
  `id` int(11) NOT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `affiliated_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agent`
--

CREATE TABLE `agent` (
  `id` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agent_region`
--

CREATE TABLE `agent_region` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `laboratory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `conteudo_pedido`
--

CREATE TABLE `conteudo_pedido` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cotacoes`
--

CREATE TABLE `cotacoes` (
  `id` int(11) NOT NULL,
  `archive` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dt_creation` datetime NOT NULL,
  `dt_update` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `distributors`
--

CREATE TABLE `distributors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `laboratory`
--

CREATE TABLE `laboratory` (
  `id` int(11) NOT NULL,
  `first_distributor` int(11) DEFAULT NULL,
  `second_distributor` int(11) DEFAULT NULL,
  `third_distributor` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `minimun_value` decimal(10,2) DEFAULT NULL,
  `billing_requirements` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `dt_creation` datetime NOT NULL,
  `dt_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `laboratory_agent`
--

CREATE TABLE `laboratory_agent` (
  `id` int(11) NOT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `laboratory_shop`
--

CREATE TABLE `laboratory_shop` (
  `id` int(11) NOT NULL,
  `laboratory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `parcelamento`
--

CREATE TABLE `parcelamento` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dt_creation` datetime NOT NULL,
  `laboratory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `dt_creation` datetime NOT NULL,
  `dt_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `product_laboratory`
--

CREATE TABLE `product_laboratory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `factory_value` decimal(10,2) DEFAULT NULL,
  `cost_value` decimal(10,2) DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `region`
--

CREATE TABLE `region` (
  `id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `shoplaboratory`
--

CREATE TABLE `shoplaboratory` (
  `id` int(11) NOT NULL,
  `laboratory_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `acronym` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
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
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_shop`
--

CREATE TABLE `user_shop` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affiliated`
--
ALTER TABLE `affiliated`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_935FEF485D83CC1` (`state_id`),
  ADD KEY `IDX_935FEF4898260155` (`region_id`);

--
-- Indexes for table `affiliated_laboratory`
--
ALTER TABLE `affiliated_laboratory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_380A7A332F2A371E` (`laboratory_id`),
  ADD KEY `IDX_380A7A333AFABA9D` (`affiliated_id`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agent_region`
--
ALTER TABLE `agent_region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5185A6503414710B` (`agent_id`),
  ADD KEY `IDX_5185A65098260155` (`region_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BA388B7A76ED395` (`user_id`),
  ADD KEY `IDX_BA388B74584665A` (`product_id`),
  ADD KEY `IDX_BA388B72F2A371E` (`laboratory_id`);

--
-- Indexes for table `conteudo_pedido`
--
ALTER TABLE `conteudo_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_149443EDA76ED395` (`user_id`),
  ADD KEY `IDX_149443ED4854653A` (`pedido_id`),
  ADD KEY `IDX_149443ED4584665A` (`product_id`),
  ADD KEY `IDX_149443ED2F2A371E` (`laboratory_id`);

--
-- Indexes for table `cotacoes`
--
ALTER TABLE `cotacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C9C73E83A76ED395` (`user_id`);

--
-- Indexes for table `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laboratory`
--
ALTER TABLE `laboratory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FDC719A83D305AB3` (`first_distributor`),
  ADD KEY `IDX_FDC719A8CA20CB6F` (`second_distributor`),
  ADD KEY `IDX_FDC719A8E3ABBC85` (`third_distributor`);

--
-- Indexes for table `laboratory_agent`
--
ALTER TABLE `laboratory_agent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_628A204D2F2A371E` (`laboratory_id`),
  ADD KEY `IDX_628A204D3414710B` (`agent_id`);

--
-- Indexes for table `laboratory_shop`
--
ALTER TABLE `laboratory_shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A9FBEFCE2F2A371E` (`laboratory_id`);

--
-- Indexes for table `parcelamento`
--
ALTER TABLE `parcelamento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C4EC16CEA76ED395` (`user_id`),
  ADD KEY `IDX_C4EC16CE2F2A371E` (`laboratory_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_laboratory`
--
ALTER TABLE `product_laboratory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EA046834584665A` (`product_id`),
  ADD KEY `IDX_6EA046832F2A371E` (`laboratory_id`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F62F1765D83CC1` (`state_id`);

--
-- Indexes for table `shoplaboratory`
--
ALTER TABLE `shoplaboratory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A9EC363E2F2A371E` (`laboratory_id`),
  ADD KEY `IDX_A9EC363EA76ED395` (`user_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`);

--
-- Indexes for table `user_shop`
--
ALTER TABLE `user_shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D6EB006BA76ED395` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `affiliated_laboratory`
--
ALTER TABLE `affiliated_laboratory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1543;
--
-- AUTO_INCREMENT for table `agent_region`
--
ALTER TABLE `agent_region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1062;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1455;
--
-- AUTO_INCREMENT for table `conteudo_pedido`
--
ALTER TABLE `conteudo_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=844;
--
-- AUTO_INCREMENT for table `cotacoes`
--
ALTER TABLE `cotacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `distributors`
--
ALTER TABLE `distributors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `laboratory`
--
ALTER TABLE `laboratory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `laboratory_agent`
--
ALTER TABLE `laboratory_agent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT for table `laboratory_shop`
--
ALTER TABLE `laboratory_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `parcelamento`
--
ALTER TABLE `parcelamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4597;
--
-- AUTO_INCREMENT for table `product_laboratory`
--
ALTER TABLE `product_laboratory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5674;
--
-- AUTO_INCREMENT for table `region`
--
ALTER TABLE `region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `shoplaboratory`
--
ALTER TABLE `shoplaboratory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=500;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;
--
-- AUTO_INCREMENT for table `user_shop`
--
ALTER TABLE `user_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `affiliated`
--
ALTER TABLE `affiliated`
  ADD CONSTRAINT `FK_935FEF485D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `FK_935FEF4898260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  ADD CONSTRAINT `FK_935FEF48BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `affiliated_laboratory`
--
ALTER TABLE `affiliated_laboratory`
  ADD CONSTRAINT `FK_380A7A332F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_380A7A333AFABA9D` FOREIGN KEY (`affiliated_id`) REFERENCES `affiliated` (`id`);

--
-- Limitadores para a tabela `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `FK_268B9C9DBF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `agent_region`
--
ALTER TABLE `agent_region`
  ADD CONSTRAINT `FK_5185A6503414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`),
  ADD CONSTRAINT `FK_5185A65098260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);

--
-- Limitadores para a tabela `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_BA388B72F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_BA388B74584665A` FOREIGN KEY (`product_id`) REFERENCES `product_laboratory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_BA388B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `conteudo_pedido`
--
ALTER TABLE `conteudo_pedido`
  ADD CONSTRAINT `FK_149443ED2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_149443ED4584665A` FOREIGN KEY (`product_id`) REFERENCES `product_laboratory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_149443ED4854653A` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `FK_149443EDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `cotacoes`
--
ALTER TABLE `cotacoes`
  ADD CONSTRAINT `FK_C9C73E83A76ED395` FOREIGN KEY (`user_id`) REFERENCES `affiliated` (`id`);

--
-- Limitadores para a tabela `laboratory`
--
ALTER TABLE `laboratory`
  ADD CONSTRAINT `FK_FDC719A83D305AB3` FOREIGN KEY (`first_distributor`) REFERENCES `distributors` (`id`),
  ADD CONSTRAINT `FK_FDC719A8CA20CB6F` FOREIGN KEY (`second_distributor`) REFERENCES `distributors` (`id`),
  ADD CONSTRAINT `FK_FDC719A8E3ABBC85` FOREIGN KEY (`third_distributor`) REFERENCES `distributors` (`id`);

--
-- Limitadores para a tabela `laboratory_agent`
--
ALTER TABLE `laboratory_agent`
  ADD CONSTRAINT `FK_628A204D2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_628A204D3414710B` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`);

--
-- Limitadores para a tabela `laboratory_shop`
--
ALTER TABLE `laboratory_shop`
  ADD CONSTRAINT `FK_A9FBEFCE2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`);

--
-- Limitadores para a tabela `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `FK_C4EC16CE2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_C4EC16CEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `affiliated` (`id`);

--
-- Limitadores para a tabela `product_laboratory`
--
ALTER TABLE `product_laboratory`
  ADD CONSTRAINT `FK_6EA046832F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_6EA046834584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Limitadores para a tabela `region`
--
ALTER TABLE `region`
  ADD CONSTRAINT `FK_F62F1765D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`);

--
-- Limitadores para a tabela `shoplaboratory`
--
ALTER TABLE `shoplaboratory`
  ADD CONSTRAINT `FK_A9EC363E2F2A371E` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratory` (`id`),
  ADD CONSTRAINT `FK_A9EC363EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Limitadores para a tabela `user_shop`
--
ALTER TABLE `user_shop`
  ADD CONSTRAINT `FK_D6EB006BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `affiliated` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
