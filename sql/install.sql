CREATE TABLE IF NOT EXISTS `mc_plug_featured_product` (
    `id_featured` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) NOT NULL,
    `instance_slug` varchar(100) NOT NULL DEFAULT 'default',
    `position` int(11) DEFAULT 0,
    PRIMARY KEY (`id_featured`),
    UNIQUE KEY `product_instance` (`id_product`, `instance_slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;