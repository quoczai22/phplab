-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: buoi10_ecommerce
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `buoi10_ecommerce`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `buoi10_ecommerce` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `buoi10_ecommerce`;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `district` varchar(255) DEFAULT NULL,
  `ward` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,3,'Lê Thị Khách Hàng','0987654321','140 Lê Trọng Tấn, Phường Tây Thạnh','TP. Hồ Chí Minh','Quận Tân Phú','Phường Tây Thạnh',1,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Zara Studio','zara-studio','frontend/images/logo.png','2026-10-26 00:35:25','2026-10-26 00:35:25'),(2,'Gucci Luxury','gucci-luxury','frontend/images/logo.png','2026-10-26 00:35:25','2026-10-26 00:35:25'),(3,'H&M Modern','hm-modern','frontend/images/logo.png','2026-10-26 00:35:25','2026-10-26 00:35:25'),(4,'Uniqlo LifeWear','uniqlo-lifewear','frontend/images/logo.png','2026-10-26 00:35:25','2026-10-26 00:35:25'),(5,'Levi\'s Denim','levis-denim','frontend/images/logo.png','2026-10-26 00:35:25','2026-10-26 00:35:25');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` varchar(255) NOT NULL DEFAULT 'all',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Thời Trang Nam','thoi-trang-nam','frontend/images/men.jpg','men','2026-10-26 00:35:25','2026-10-26 00:35:25'),(2,'Thời Trang Nữ','thoi-trang-nu','frontend/images/women.jpg','women','2026-10-26 00:35:25','2026-10-26 00:35:25'),(3,'Đầm & Váy Dự Tiệc','dam-vay-du-tiec','frontend/images/pi.jpg','women','2026-10-26 00:35:25','2026-10-26 00:35:25'),(4,'Áo Khoác & Denim','ao-khoac-denim','frontend/images/men1.jpg','all','2026-10-26 00:35:25','2026-10-26 00:35:25'),(5,'Áo Thun & Tanktop','ao-thun-tanktop','frontend/images/po.jpg','all','2026-10-26 00:35:25','2026-10-26 00:35:25'),(6,'Bộ Sưu Tập Mùa Hè','bo-suu-tap-mua-he','frontend/images/pi4.jpg','all','2026-10-26 00:35:25','2026-10-26 00:35:25');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `type` enum('fixed','percent') NOT NULL DEFAULT 'percent',
  `value` decimal(12,2) NOT NULL,
  `cart_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `expiry_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'OFF10','percent',10.00,100000.00,'2027-01-26','2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,'SUMMER20','percent',20.00,200000.00,'2026-12-26','2026-10-26 00:35:26','2026-10-26 00:35:26'),(3,'GIAM50','fixed',50000.00,150000.00,'2027-04-26','2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `options` varchar(255) DEFAULT NULL,
  `rstatus` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_variant_id_foreign` (`variant_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,NULL,149000.00,1,'Size: M | Màu: Đen',0,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,1,2,NULL,169000.00,1,'Size: S | Màu: Trắng',0,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(3,2,5,NULL,199000.00,1,'Size: L | Màu: Xanh Navy',0,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(4,3,3,NULL,125000.00,1,'Size: M | Màu: Trắng',0,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_notifications`
--

DROP TABLE IF EXISTS `order_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'order',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `for_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_notifications_user_id_foreign` (`user_id`),
  KEY `order_notifications_order_id_foreign` (`order_id`),
  CONSTRAINT `order_notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_notifications`
--

LOCK TABLES `order_notifications` WRITE;
/*!40000 ALTER TABLE `order_notifications` DISABLE KEYS */;
INSERT INTO `order_notifications` VALUES (1,3,2,'Đơn hàng ORD-2026002 đang được giao','Shipper Nguyễn Văn Nhân Viên (0902345678) đang giao đơn hàng của bạn.','order',0,0,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,3,3,'Đơn hàng mới: ORD-2026003','Khách hàng Trần Minh Quân vừa đặt đơn hàng trị giá 137.500 đ','order',0,1,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `order_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `payment_mode` varchar(255) NOT NULL DEFAULT 'cod',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `status` varchar(255) NOT NULL DEFAULT 'ordered',
  `shipper_name` varchar(255) DEFAULT NULL,
  `shipper_phone` varchar(255) DEFAULT NULL,
  `tracking_note` varchar(255) DEFAULT NULL,
  `delivered_date` timestamp NULL DEFAULT NULL,
  `canceled_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-2026001',3,318000.00,31800.00,28620.00,314820.00,'Lê Thị Khách Hàng','0987654321','140 Lê Trọng Tấn, Quận Tân Phú','TP. Hồ Chí Minh','vnpay','paid','delivered','Nguyễn Văn Nhân Viên','0902345678','Giao hàng thành công tại sảnh chính','2026-10-24 00:35:26',NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,'ORD-2026002',3,199000.00,0.00,19900.00,218900.00,'Lê Thị Khách Hàng','0987654321','140 Lê Trọng Tấn, Quận Tân Phú','TP. Hồ Chí Minh','cod','pending','shipping','Nguyễn Văn Nhân Viên','0902345678','Shipper đang trên đường giao đến Quận Tân Phú',NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(3,'ORD-2026003',3,125000.00,0.00,12500.00,137500.00,'Trần Minh Quân','0912345678','25 Nguyễn Huệ, Quận 1','TP. Hồ Chí Minh','momo','paid','ordered',NULL,NULL,'Đơn hàng mới tạo, đang chờ xác nhận',NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `size` varchar(255) NOT NULL DEFAULT 'M',
  `color` varchar(255) NOT NULL DEFAULT 'Black',
  `sku` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,'S','Đen','DNM-W001-S',149000.00,9,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,1,'M','Trắng','DNM-W001-M',149000.00,8,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(3,1,'L','Xanh Navy','DNM-W001-L',149000.00,5,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(4,1,'XL','Đỏ Đô','DNM-W001-XL',149000.00,13,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(5,2,'S','Đen','DRS-W002-S',139000.00,10,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(6,2,'M','Trắng','DRS-W002-M',139000.00,17,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(7,2,'L','Xanh Navy','DRS-W002-L',139000.00,16,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(8,2,'XL','Đỏ Đô','DRS-W002-XL',139000.00,15,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(9,3,'S','Đen','BLS-W003-S',125000.00,16,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(10,3,'M','Trắng','BLS-W003-M',125000.00,17,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(11,3,'L','Xanh Navy','BLS-W003-L',125000.00,5,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(12,3,'XL','Đỏ Đô','BLS-W003-XL',125000.00,12,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(13,4,'S','Đen','STR-W004-S',119000.00,18,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(14,4,'M','Trắng','STR-W004-M',119000.00,13,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(15,4,'L','Xanh Navy','STR-W004-L',119000.00,12,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(16,4,'XL','Đỏ Đô','STR-W004-XL',119000.00,20,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(17,5,'S','Đen','MEN-B005-S',165000.00,9,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(18,5,'M','Trắng','MEN-B005-M',165000.00,16,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(19,5,'L','Xanh Navy','MEN-B005-L',165000.00,8,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(20,5,'XL','Đỏ Đô','MEN-B005-XL',165000.00,13,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(21,6,'S','Đen','MEN-S006-S',119000.00,10,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(22,6,'M','Trắng','MEN-S006-M',119000.00,7,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(23,6,'L','Xanh Navy','MEN-S006-L',119000.00,10,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(24,6,'XL','Đỏ Đô','MEN-S006-XL',119000.00,16,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(25,7,'S','Đen','MEN-C007-S',239000.00,9,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(26,7,'M','Trắng','MEN-C007-M',239000.00,15,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(27,7,'L','Xanh Navy','MEN-C007-L',239000.00,13,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(28,7,'XL','Đỏ Đô','MEN-C007-XL',239000.00,13,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(29,8,'S','Đen','TNK-008-S',59950.00,18,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(30,8,'M','Trắng','TNK-008-M',59950.00,18,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(31,8,'L','Xanh Navy','TNK-008-L',59950.00,19,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(32,8,'XL','Đỏ Đô','TNK-008-XL',59950.00,13,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(33,9,'S','Đen','TNK-009-S',45550.00,16,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(34,9,'M','Trắng','TNK-009-M',45550.00,17,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(35,9,'L','Xanh Navy','TNK-009-L',45550.00,18,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(36,9,'XL','Đỏ Đô','TNK-009-XL',45550.00,9,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(37,10,'S','Đen','BOH-010-S',59950.00,19,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(38,10,'M','Trắng','BOH-010-M',59950.00,11,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(39,10,'L','Xanh Navy','BOH-010-L',59950.00,14,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(40,10,'XL','Đỏ Đô','BOH-010-XL',59950.00,7,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(41,11,'S','Đen','DNM-011-S',69950.00,19,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(42,11,'M','Trắng','DNM-011-M',69950.00,8,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(43,11,'L','Xanh Navy','DNM-011-L',69950.00,10,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(44,11,'XL','Đỏ Đô','DNM-011-XL',69950.00,16,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(45,12,'S','Đen','GWN-012-S',199000.00,7,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(46,12,'M','Trắng','GWN-012-M',199000.00,9,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(47,12,'L','Xanh Navy','GWN-012-L',199000.00,18,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(48,12,'XL','Đỏ Đô','GWN-012-XL',199000.00,12,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `regular_price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `sku` varchar(255) NOT NULL,
  `stock_status` enum('instock','outofstock') NOT NULL DEFAULT 'instock',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `quantity` int(10) unsigned NOT NULL DEFAULT 10,
  `image` varchar(255) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `gender` varchar(255) NOT NULL DEFAULT 'women',
  `is_flash_sale` tinyint(1) NOT NULL DEFAULT 0,
  `flash_sale_price` decimal(12,2) DEFAULT NULL,
  `flash_sale_end` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Premium Denim Women\'s Hidden Jacket','premium-denim-womens-hidden-jacket','Áo khoác Denim cao cấp thiết kế trẻ trung, chất liệu cotton thoáng mát.','Sản phẩm áo khoác Denim nữ cao cấp nằm trong bộ sưu tập mới nhất. Chất vải mềm mại, giữ form tốt, dễ dàng phối cùng quần jeans hoặc chân váy.',210000.00,179000.00,'DNM-W001','instock',1,45,'frontend/images/pi.jpg','frontend/images/sin1.jpg,frontend/images/sin2.jpg,frontend/images/sin3.jpg,frontend/images/sin4.jpg',2,5,'women',1,149000.00,'2026-10-31 00:35:25','2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,'Summer Floral Silk Dress','summer-floral-silk-dress','Đầm lụa họa tiết mùa hè thanh lịch, tôn dáng.','Thiết kế đầm lụa cao cấp mang phong cách châu Âu hiện đại, phù hợp đi dạo phố, công sở và dự tiệc nhẹ.',199000.00,169000.00,'DRS-W002','instock',1,30,'frontend/images/pi4.jpg','frontend/images/sin2.jpg,frontend/images/sin1.jpg,frontend/images/sin3.jpg',3,1,'women',1,139000.00,'2026-10-31 00:35:25','2026-10-26 00:35:26','2026-10-26 00:35:26'),(3,'Elegant Evening Chic Blouse','elegant-evening-chic-blouse','Áo kiểu nữ sang trọng phối chân váy công sở.','Chất liệu voan lụa mềm mịn, thoáng khí, đường may tỉ mỉ sắc nét.',150000.00,125000.00,'BLS-W003','instock',1,28,'frontend/images/pi5.jpg','frontend/images/sin3.jpg,frontend/images/sin1.jpg',2,2,'women',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(4,'Modern Streetwear Kurti Top','modern-streetwear-kurti-top','Áo khoác nhẹ nữ phong cách Streetwear năng động.','Thiết kế cá tính, màu sắc trẻ trung, phù hợp cho các buổi dạo phố hoặc du lịch.',185000.00,155000.00,'STR-W004','instock',0,50,'frontend/images/pi6.jpg','frontend/images/sin4.jpg,frontend/images/sin2.jpg',6,3,'women',1,119000.00,'2026-10-29 00:35:25','2026-10-26 00:35:26','2026-10-26 00:35:26'),(5,'Men\'s Classic Oxford Blazer','mens-classic-oxford-blazer','Áo vest/blazer nam lịch lãm phong cách quý ông.','Form dáng Slim-fit tôn dáng, chất vải cao cấp chống nhăn, thích hợp cho sự kiện và công sở.',250000.00,199000.00,'MEN-B005','instock',1,35,'frontend/images/men.jpg','frontend/images/sin1.jpg,frontend/images/sin2.jpg',1,2,'men',1,165000.00,'2026-10-30 00:35:25','2026-10-26 00:35:26','2026-10-26 00:35:26'),(6,'Men\'s Urban Casual Shirt','mens-urban-casual-shirt','Áo sơ mi nam dài tay chất cotton thấm hút mồ hôi.','Sơ mi nam thiết kế tối giản, lịch sự, dễ phối với quần âu hoặc jeans.',140000.00,119000.00,'MEN-S006','instock',1,60,'frontend/images/men1.jpg','frontend/images/sin2.jpg,frontend/images/sin3.jpg',1,4,'men',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(7,'Men\'s Winter Trench Coat','mens-winter-trench-coat','Áo khoác măng tô nam giữ ấm cao cấp.','Chất liệu dạ pha len cao cấp, giữ nhiệt tốt, đường cắt may chuẩn form quốc tế.',290000.00,239000.00,'MEN-C007','instock',0,20,'frontend/images/men2.jpg','frontend/images/sin3.jpg,frontend/images/sin4.jpg',4,1,'men',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(8,'Feel Tank Sporty Top','feel-tank-sporty-top','Áo tanktop thể thao co giãn 4 chiều.','Phù hợp tập gym, yoga hoặc mặc dạo phố mùa hè.',75000.00,59950.00,'TNK-008','instock',1,80,'frontend/images/po.jpg','frontend/images/sin1.jpg,frontend/images/sin4.jpg',5,3,'women',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(9,'Pintuck Designer Tank','pintuck-designer-tank','Áo thun thiết kế trẻ trung năng động.','Chất liệu 100% Organic Cotton thân thiện với làn da.',65000.00,45550.00,'TNK-009','instock',0,40,'frontend/images/po5.jpg','frontend/images/sin2.jpg',5,4,'women',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(10,'Boho Chic Summer Tunic','boho-chic-summer-tunic','Áo tunic họa tiết Boho phóng khoáng.','Thiết kế độc đáo cho mùa lễ hội và kỳ nghỉ biển.',89000.00,59950.00,'BOH-010','instock',0,25,'frontend/images/po1.jpg','frontend/images/sin3.jpg',6,1,'women',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(11,'Vintage Indigo Jeans Shirt','vintage-indigo-jeans-shirt','Áo sơ mi牛仔/Denim phong cách cổ điển.','Màu chàm tự nhiên, càng mặc càng đẹp.',95000.00,69950.00,'DNM-011','instock',1,18,'frontend/images/po2.jpg','frontend/images/sin4.jpg',4,5,'women',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(12,'Royal Silk Evening Gown','royal-silk-evening-gown','Đầm dạ hội lụa cao cấp sang trọng.','Tôn vinh vẻ đẹp quý phái trong mọi bữa tiệc.',260000.00,199000.00,'GWN-012','instock',1,12,'frontend/images/po3.jpg','frontend/images/sin1.jpg',3,2,'women',0,NULL,NULL,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_requests`
--

DROP TABLE IF EXISTS `return_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('return','exchange') NOT NULL DEFAULT 'return',
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_requests_order_id_foreign` (`order_id`),
  KEY `return_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `return_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_requests`
--

LOCK TABLES `return_requests` WRITE;
/*!40000 ALTER TABLE `return_requests` DISABLE KEYS */;
INSERT INTO `return_requests` VALUES (1,1,3,'exchange','Muốn đổi từ Size S sang Size M cho vừa vặn hơn.','pending','Đang kiểm tra tồn kho Size M','2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `return_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `comment` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,3,1,5,'Áo khoác Denim cực đẹp, chất vải dày dặn, giao hàng nhanh!',1,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,1,1,4,'Form chuẩn, màu sắc giống hình 100%.',1,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(3,3,2,5,'Đầm mặc rất mát và sang, sẽ ủng hộ shop tiếp!',1,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tagline` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Collection','Summer 2026','Helping you look cool - Sale up to 40% Off','/collection','frontend/images/13.png',1,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,'New Arrivals','Night Spring','Luxury Dresses & Streetwear','/women','frontend/images/13.png',1,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Trịnh Hữu Kiến Quốc (Admin)','admin@fashionstore.com','0901234567','admin','admin/images/avatar/user-1.png',NULL,'$2y$10$8BUBIZypdiG7gbCjWqD4FewZ/rdiWHQgulJfcbb7pYccPj0DyW6Vq',NULL,'2026-10-26 00:35:25','2026-10-26 00:35:25'),(2,'Nguyễn Văn Nhân Viên','staff@fashionstore.com','0902345678','staff','admin/images/avatar/user-1.png',NULL,'$2y$10$xT37C64FqT89.7fJJSRrde4h6KW8vJcCeG5TDyZNNPUl5YZ2dxPza',NULL,'2026-10-26 00:35:25','2026-10-26 00:35:25'),(3,'Lê Thị Khách Hàng','customer@fashionstore.com','0987654321','customer','admin/images/avatar/user-1.png',NULL,'$2y$10$ROR7WmpTLewFq6FhjFSC2uPqfcsRDXyI9EuUZ7jzvqv5BoBcwDfBG',NULL,'2026-10-26 00:35:25','2026-10-26 00:35:25');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wishlists_user_id_foreign` (`user_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
INSERT INTO `wishlists` VALUES (1,3,1,'2026-10-26 00:35:26','2026-10-26 00:35:26'),(2,3,5,'2026-10-26 00:35:26','2026-10-26 00:35:26');
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
