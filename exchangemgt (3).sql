-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 28 fév. 2022 à 10:35
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `exchangemgt`
--

-- --------------------------------------------------------

--
-- Structure de la table `branchs`
--

CREATE TABLE `branchs` (
  `bbid` int(11) NOT NULL,
  `bbBrancheName` varchar(255) NOT NULL,
  `bbCaissier` int(11) NOT NULL,
  `bbLocation` varchar(255) NOT NULL,
  `bbBalance` double NOT NULL,
  `bbCurrencyType` int(11) NOT NULL,
  `bbDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `branchs`
--

INSERT INTO `branchs` (`bbid`, `bbBrancheName`, `bbCaissier`, `bbLocation`, `bbBalance`, `bbCurrencyType`, `bbDate`) VALUES
(16, 'B300', 13, 'CASA', 3500, 1, '2022-02-18 14:01:11'),
(18, 'capital NKT', 11, 'NKC', 15000, 5, '2022-02-20 07:34:08'),
(19, 'c888', 12, 'USA', 400, 2, '2022-02-26 15:49:45');

-- --------------------------------------------------------

--
-- Structure de la table `chargerbranch`
--

CREATE TABLE `chargerbranch` (
  `id` int(11) NOT NULL,
  `idSupp` int(11) NOT NULL,
  `idBranch` int(11) NOT NULL,
  `amountDevise` double NOT NULL,
  `amountMRU` double NOT NULL,
  `amountPaye` double NOT NULL,
  `add_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `chargerbranch`
--

INSERT INTO `chargerbranch` (`id`, `idSupp`, `idBranch`, `amountDevise`, `amountMRU`, `amountPaye`, `add_at`) VALUES
(4, 5, 16, 600, 2334, 2000, '2022-02-18 21:24:58'),
(7, 1, 16, 1000, 3900, 2000, '2022-02-24 14:28:37');

-- --------------------------------------------------------

--
-- Structure de la table `ratebranchs`
--

CREATE TABLE `ratebranchs` (
  `idRR` int(11) NOT NULL,
  `idBranchRR` int(11) NOT NULL,
  `nameRR` varchar(255) NOT NULL,
  `currencyRR` varchar(255) NOT NULL,
  `cost_price` double NOT NULL,
  `retail_price` double NOT NULL,
  `add_at` datetime NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ratebranchs`
--

INSERT INTO `ratebranchs` (`idRR`, `idBranchRR`, `nameRR`, `currencyRR`, `cost_price`, `retail_price`, `add_at`, `update_at`) VALUES
(7, 16, 'dollard American', 'USD', 9.41, 10.00365, '2022-02-23 12:43:11', '2022-02-23 13:19:12'),
(8, 18, 'Maroc Dirham', 'MAD', 3.81, 4.12, '2022-02-23 13:13:11', '2022-02-23 18:18:30'),
(10, 16, 'Ouguiya', 'MRU', 0.2614, 0.31, '2022-02-23 13:19:39', '2022-02-23 13:28:39'),
(15, 18, 'dollard American', 'USD', 36.009, 38.16, '2022-02-23 18:13:46', '2022-02-23 18:18:09'),
(16, 18, 'Ouguiya', 'MRU', 1, 1, '2022-02-23 20:22:47', '2022-02-23 20:22:47'),
(18, 19, 'Maroc Dirham', 'MAD', 0.1053, 0.123, '2022-02-27 17:33:40', '2022-02-27 17:33:40'),
(19, 19, 'Ouguiya', 'MRU', 0.0274, 0.029, '2022-02-27 17:34:31', '2022-02-27 17:34:31'),
(20, 19, 'dollard American', 'USD', 1, 1, '2022-02-27 17:34:44', '2022-02-27 17:34:44'),
(21, 16, 'Maroc Dirham', 'MAD', 1, 1, '2022-02-27 17:38:41', '2022-02-27 17:38:41');

-- --------------------------------------------------------

--
-- Structure de la table `rates`
--

CREATE TABLE `rates` (
  `idRR` int(11) NOT NULL,
  `nameRR` varchar(255) NOT NULL,
  `currencyRR` varchar(255) NOT NULL,
  `cost_price` double NOT NULL,
  `retail_price` double NOT NULL,
  `add_at` datetime NOT NULL,
  `update_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rates`
--

INSERT INTO `rates` (`idRR`, `nameRR`, `currencyRR`, `cost_price`, `retail_price`, `add_at`, `update_at`) VALUES
(1, 'Maroc Dirham', 'MAD', 3.9, 4.12, '2022-02-12 21:11:25', '2022-02-24 14:23:02'),
(2, 'dollard American', 'USD', 36.16, 38.56, '2022-02-12 23:36:59', '2022-02-22 09:29:19'),
(5, 'Ouguiya', 'MRU', 1, 1, '2022-02-20 07:31:12', '2022-02-23 13:25:58');

-- --------------------------------------------------------

--
-- Structure de la table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `BoutiqueName` varchar(255) NOT NULL,
  `ssDette` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `suppliers`
--

INSERT INTO `suppliers` (`id`, `FullName`, `BoutiqueName`, `ssDette`) VALUES
(1, 'Nabil', 'B100', 1400),
(5, 'karimTakTak', 'Ivry-66', 3005.6);

-- --------------------------------------------------------

--
-- Structure de la table `zzcustomers`
--

CREATE TABLE `zzcustomers` (
  `ccID` int(11) NOT NULL,
  `ccFullName` varchar(255) NOT NULL,
  `ccCellphone` varchar(255) NOT NULL,
  `ccCarteID` varchar(255) NOT NULL,
  `ccAddress` int(11) NOT NULL,
  `ccSolde` double(10,3) NOT NULL,
  `ccAddBy` int(11) NOT NULL,
  `ccApprove` int(11) NOT NULL DEFAULT 0,
  `ccAddAt` datetime NOT NULL,
  `ccUpdateAt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `zzcustomers`
--

INSERT INTO `zzcustomers` (`ccID`, `ccFullName`, `ccCellphone`, `ccCarteID`, `ccAddress`, `ccSolde`, `ccAddBy`, `ccApprove`, `ccAddAt`, `ccUpdateAt`) VALUES
(8, 'onizuka', '36123659', '232656512', 16, 1700.000, 1, 0, '2022-02-20 14:18:11', '2022-02-20 22:00:01'),
(10, 'ademo', '36138613', '049285656', 18, 4100.000, 1, 1, '2022-02-25 17:53:24', ''),
(11, 'khey', '32010171', '98565656', 18, -1000.000, 1, 1, '2022-02-25 19:03:37', '');

-- --------------------------------------------------------

--
-- Structure de la table `zznocustomers`
--

CREATE TABLE `zznocustomers` (
  `nnID` int(11) NOT NULL,
  `nnBranchSender` int(11) NOT NULL,
  `nnSenderContact` varchar(255) NOT NULL,
  `nnBranchReceipt` int(11) NOT NULL,
  `nnReceiptContact` varchar(255) NOT NULL,
  `nnReceiptName` varchar(255) NOT NULL,
  `nnAmountSend` double(10,3) NOT NULL,
  `nnAmountReceipt` double(10,3) NOT NULL,
  `nnBenef` decimal(10,3) NOT NULL,
  `nnType` varchar(200) NOT NULL,
  `nnValider` int(11) NOT NULL DEFAULT 0,
  `nnDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `zznocustomers`
--

INSERT INTO `zznocustomers` (`nnID`, `nnBranchSender`, `nnSenderContact`, `nnBranchReceipt`, `nnReceiptContact`, `nnReceiptName`, `nnAmountSend`, `nnAmountReceipt`, `nnBenef`, `nnType`, `nnValider`, `nnDate`) VALUES
(1, 18, '36138613', 16, '32010171', 'khey', 1000.000, 242.718, '310.000', 'Envoie', 0, '2022-02-01 09:38:11'),
(3, 18, '34150102', 19, '36138613', 'pnl', 3000.000, 78.616, '6453.000', 'Envoie', 1, '2022-02-27 14:13:25'),
(4, 16, '46461983', 18, '22234845', 'znb', 300.000, 967.742, '14.580', 'Envoie', 0, '2022-02-27 16:20:53'),
(5, 16, '2222222', 18, '36151412', 'hmd', 200.000, 645.161, '9.720', 'Envoie', 0, '2022-02-27 16:21:42'),
(6, 16, '26266768', 18, '22461983', 'Meimouna', 500.000, 1612.903, '24.300', 'Envoie', 0, '2022-02-27 18:18:33'),
(7, 19, '636378', 18, '737373', 'Medd', 100.000, 3448.276, '0.160', 'Envoie', 0, '2022-02-27 18:28:05');

-- --------------------------------------------------------

--
-- Structure de la table `zzpaysupplier`
--

CREATE TABLE `zzpaysupplier` (
  `ppid` int(11) NOT NULL,
  `ppidSupp` int(11) NOT NULL,
  `ppPay` double NOT NULL,
  `ppType` varchar(200) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `zzpaysupplier`
--

INSERT INTO `zzpaysupplier` (`ppid`, `ppidSupp`, `ppPay`, `ppType`, `date`) VALUES
(1, 1, 3000, '', '2022-02-18 13:59:46'),
(2, 1, 580, '', '2022-02-18 14:00:05'),
(3, 1, 56, '', '2022-02-18 14:24:43'),
(5, 5, 6000, '', '2022-02-20 22:30:35'),
(6, 1, 500, '', '2022-02-24 14:29:18');

-- --------------------------------------------------------

--
-- Structure de la table `zztransactions`
--

CREATE TABLE `zztransactions` (
  `ttID` int(11) NOT NULL,
  `ttidBranch` int(11) NOT NULL,
  `ttFromCurrency` int(11) NOT NULL,
  `ttToCurrency` int(11) NOT NULL,
  `ttMontant` double(10,3) NOT NULL,
  `ttNetConvert` double(10,3) NOT NULL,
  `ttBenef` double(10,3) NOT NULL,
  `ttType` varchar(255) NOT NULL,
  `ttDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `zztransactions`
--

INSERT INTO `zztransactions` (`ttID`, `ttidBranch`, `ttFromCurrency`, `ttToCurrency`, `ttMontant`, `ttNetConvert`, `ttBenef`, `ttType`, `ttDate`) VALUES
(11, 18, 16, 8, 2000.000, 524.934, 620.000, 'buy', '2022-02-23 20:27:53'),
(12, 18, 8, 16, 200.000, 824.000, 62.000, 'Sale', '2022-02-24 13:31:35'),
(13, 18, 16, 15, 200.000, 5.554, 430.200, 'buy', '2022-02-24 14:01:44'),
(16, 18, 16, 8, 300.000, 78.740, 93.000, 'Achat', '2022-02-25 19:06:34'),
(17, 18, 8, 16, 200.000, 824.000, 62.000, 'Vente', '2022-02-25 19:06:46');

-- --------------------------------------------------------

--
-- Structure de la table `zztranscustomer`
--

CREATE TABLE `zztranscustomer` (
  `tcID` int(11) NOT NULL,
  `tcIdCust` int(11) NOT NULL,
  `tcPhone` varchar(255) NOT NULL,
  `tcFullName` varchar(255) NOT NULL,
  `tcAmount` double(10,3) NOT NULL,
  `tcType` varchar(255) NOT NULL,
  `tcDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `zztranscustomer`
--

INSERT INTO `zztranscustomer` (`tcID`, `tcIdCust`, `tcPhone`, `tcFullName`, `tcAmount`, `tcType`, `tcDate`) VALUES
(1, 10, '36138613', 'ademo', 200.000, 'versement', '2022-02-25 18:31:12'),
(2, 11, '32010171', 'khey', 300.000, 'versement', '2022-02-25 19:03:49'),
(3, 10, '36138613', 'ademo', 400.000, 'retrait', '2022-02-25 19:46:52'),
(4, 10, '36138613', 'ademo', 100.000, 'retrait', '2022-02-25 19:47:21'),
(5, 11, '32010171', 'khey', 1000.000, 'retrait', '2022-02-25 19:54:15'),
(6, 11, '32010171', 'khey', 200.000, 'retrait', '2022-02-26 16:08:33'),
(7, 11, '32010171', 'khey', 600.000, 'retrait', '2022-02-26 16:09:01'),
(8, 10, '36138613', 'ademo', 200.000, 'retrait', '2022-02-26 16:13:45');

-- --------------------------------------------------------

--
-- Structure de la table `zzusers`
--

CREATE TABLE `zzusers` (
  `uuid` int(11) NOT NULL,
  `uuUsername` varchar(255) NOT NULL,
  `uuPassword` varchar(255) NOT NULL,
  `uuFullName` varchar(255) NOT NULL,
  `uuCellphone` bigint(20) NOT NULL,
  `uuStatus` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `zzusers`
--

INSERT INTO `zzusers` (`uuid`, `uuUsername`, `uuPassword`, `uuFullName`, `uuCellphone`, `uuStatus`) VALUES
(1, 'sniper', '$2y$10$k1GCn9Dq.p2o2YZTqY40febSkVPSR1zNCx3KCq96iOswrJ0WO0mJu', 'Sniper QLF', 36138613, 1),
(7, 'nabil', '1234', 'nabil', 36655656, 0),
(11, 'bene', '$2y$10$iY.Kl5QvlUoUPP7DtevqfuTo7xEBcTKDUFXWpo9Cn5Z.AjB88ke2G', 'bene pnl', 36138611, 0),
(12, 'elhady', '$2y$10$jbE7BX6ClFYdjxZ7gypTMurWI8wOK82A3446Cdp6m0AjnmU3KgYdm', 'elhady', 38193819, 0),
(13, 'karim', '$2y$10$dceh4mz.rjuRMxAWMACe0eFvu9d/.K6sJvpaNYHnTWbWfD91WMhrC', 'karim', 52653265, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `branchs`
--
ALTER TABLE `branchs`
  ADD PRIMARY KEY (`bbid`),
  ADD KEY `uuCurrencyType` (`bbCurrencyType`),
  ADD KEY `bbCaissier` (`bbCaissier`);

--
-- Index pour la table `chargerbranch`
--
ALTER TABLE `chargerbranch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSupp` (`idSupp`),
  ADD KEY `idBranch` (`idBranch`);

--
-- Index pour la table `ratebranchs`
--
ALTER TABLE `ratebranchs`
  ADD PRIMARY KEY (`idRR`),
  ADD KEY `idBranchRR` (`idBranchRR`);

--
-- Index pour la table `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`idRR`);

--
-- Index pour la table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `zzcustomers`
--
ALTER TABLE `zzcustomers`
  ADD PRIMARY KEY (`ccID`),
  ADD KEY `ccAddBy` (`ccAddBy`),
  ADD KEY `ccAddress` (`ccAddress`);

--
-- Index pour la table `zznocustomers`
--
ALTER TABLE `zznocustomers`
  ADD PRIMARY KEY (`nnID`),
  ADD KEY `nnBranchSender` (`nnBranchSender`),
  ADD KEY `nnBranchReceipt` (`nnBranchReceipt`);

--
-- Index pour la table `zzpaysupplier`
--
ALTER TABLE `zzpaysupplier`
  ADD PRIMARY KEY (`ppid`),
  ADD KEY `ppidSupp` (`ppidSupp`);

--
-- Index pour la table `zztransactions`
--
ALTER TABLE `zztransactions`
  ADD PRIMARY KEY (`ttID`),
  ADD KEY `eeidBranch` (`ttidBranch`),
  ADD KEY `ttFromCurrency` (`ttFromCurrency`),
  ADD KEY `ttToCurrency` (`ttToCurrency`);

--
-- Index pour la table `zztranscustomer`
--
ALTER TABLE `zztranscustomer`
  ADD PRIMARY KEY (`tcID`),
  ADD KEY `zztranscustomer_ibfk_1` (`tcIdCust`);

--
-- Index pour la table `zzusers`
--
ALTER TABLE `zzusers`
  ADD PRIMARY KEY (`uuid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `branchs`
--
ALTER TABLE `branchs`
  MODIFY `bbid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `chargerbranch`
--
ALTER TABLE `chargerbranch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ratebranchs`
--
ALTER TABLE `ratebranchs`
  MODIFY `idRR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `rates`
--
ALTER TABLE `rates`
  MODIFY `idRR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `zzcustomers`
--
ALTER TABLE `zzcustomers`
  MODIFY `ccID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `zznocustomers`
--
ALTER TABLE `zznocustomers`
  MODIFY `nnID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `zzpaysupplier`
--
ALTER TABLE `zzpaysupplier`
  MODIFY `ppid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `zztransactions`
--
ALTER TABLE `zztransactions`
  MODIFY `ttID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `zztranscustomer`
--
ALTER TABLE `zztranscustomer`
  MODIFY `tcID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `zzusers`
--
ALTER TABLE `zzusers`
  MODIFY `uuid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `branchs`
--
ALTER TABLE `branchs`
  ADD CONSTRAINT `branchs_ibfk_1` FOREIGN KEY (`bbCurrencyType`) REFERENCES `rates` (`idRR`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `branchs_ibfk_2` FOREIGN KEY (`bbCaissier`) REFERENCES `zzusers` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `chargerbranch`
--
ALTER TABLE `chargerbranch`
  ADD CONSTRAINT `chargerbranch_ibfk_1` FOREIGN KEY (`idSupp`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chargerbranch_ibfk_2` FOREIGN KEY (`idBranch`) REFERENCES `branchs` (`bbid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ratebranchs`
--
ALTER TABLE `ratebranchs`
  ADD CONSTRAINT `ratebranchs_ibfk_1` FOREIGN KEY (`idBranchRR`) REFERENCES `branchs` (`bbid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `zzcustomers`
--
ALTER TABLE `zzcustomers`
  ADD CONSTRAINT `zzcustomers_ibfk_1` FOREIGN KEY (`ccAddBy`) REFERENCES `zzusers` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zzcustomers_ibfk_2` FOREIGN KEY (`ccAddress`) REFERENCES `branchs` (`bbid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `zznocustomers`
--
ALTER TABLE `zznocustomers`
  ADD CONSTRAINT `zznocustomers_ibfk_1` FOREIGN KEY (`nnBranchSender`) REFERENCES `branchs` (`bbid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zznocustomers_ibfk_2` FOREIGN KEY (`nnBranchReceipt`) REFERENCES `branchs` (`bbid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `zzpaysupplier`
--
ALTER TABLE `zzpaysupplier`
  ADD CONSTRAINT `zzpaysupplier_ibfk_1` FOREIGN KEY (`ppidSupp`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `zztransactions`
--
ALTER TABLE `zztransactions`
  ADD CONSTRAINT `zztransactions_ibfk_1` FOREIGN KEY (`ttidBranch`) REFERENCES `branchs` (`bbid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zztransactions_ibfk_2` FOREIGN KEY (`ttFromCurrency`) REFERENCES `ratebranchs` (`idRR`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zztransactions_ibfk_3` FOREIGN KEY (`ttToCurrency`) REFERENCES `ratebranchs` (`idRR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `zztranscustomer`
--
ALTER TABLE `zztranscustomer`
  ADD CONSTRAINT `zztranscustomer_ibfk_1` FOREIGN KEY (`tcIdCust`) REFERENCES `zzcustomers` (`ccID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
