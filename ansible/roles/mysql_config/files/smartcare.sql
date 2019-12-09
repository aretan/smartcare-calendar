INSTALL SONAME 'server_audit';

START TRANSACTION;

CREATE TABLE `shoken` (
  `id` char(9) NOT NULL PRIMARY KEY COMMENT '証券番号',
  `name` varchar(255) NOT NULL COMMENT '被保険者名',
  `date` date NOT NULL COMMENT '契約開始日',
  `comment` text DEFAULT NULL COMMENT '査定者コメント',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL
) COMMENT='証券';

CREATE TABLE `ukeban` (
  `id` char(14) NOT NULL PRIMARY KEY COMMENT '受付番号',
  `shoken_id` char(9) NOT NULL COMMENT '証券番号',
  `date` date NOT NULL COMMENT '受付日付',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX(shoken_id),
  INDEX(date) -- ORDER BY `date`
) COMMENT='受付番号';

CREATE TABLE `shujutsu` (
  `id` int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT '手術ID (自動採番)',
  `shoken_id` char(9) NOT NULL COMMENT '証券番号',
  `ukeban_id` char(14) NOT NULL COMMENT '受付番号',
  `date` date NOT NULL COMMENT '手術日',
  `warrantyStart` date NOT NULL COMMENT '保証開始日',
  `warrantyEnd` date NOT NULL COMMENT '保証終了日',
  `warrantyMax` int(11) NOT NULL COMMENT '保証回数',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX(shoken_id, ukeban_id),
  INDEX(warrantyStart) -- ORDER BY `warrantyStart`
) COMMENT='手術';

CREATE TABLE `nyuin` (
  `id` int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT '入院ID (自動採番)',
  `shoken_id` char(9) NOT NULL COMMENT '証券番号',
  `ukeban_id` char(14) NOT NULL COMMENT '受付番号',
  `start` date NOT NULL COMMENT '入院開始日',
  `end` date NOT NULL COMMENT '入院終了日',
  `warrantyStart` date NOT NULL COMMENT '保証開始日',
  `warrantyEnd` date NOT NULL COMMENT '保証終了日',
  `warrantyMax` int(11) NOT NULL COMMENT '保証回数',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX(shoken_id, ukeban_id),
  INDEX(warrantyStart) -- ORDER BY `warrantyStart`
) COMMENT='入院';

CREATE TABLE `tsuin` (
  `id` int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT '通院ID (自動採番)',
  `shoken_id` char(9) NOT NULL COMMENT '証券番号',
  `ukeban_id` char(14) NOT NULL COMMENT '受付番号',
  `date` date NOT NULL COMMENT '通院日',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX(shoken_id, ukeban_id),
  INDEX(date) -- ORDER BY `date`
) COMMENT='通院';

CREATE TABLE `bunsho` (
  `id` int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT '通院ID (自動採番)',
  `shoken_id` char(9) NOT NULL COMMENT '証券番号',
  `ukeban_id` char(14) NOT NULL COMMENT '受付番号',
  `date` date NOT NULL COMMENT '文書日',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  INDEX(shoken_id, ukeban_id),
  INDEX(date) -- ORDER BY `date`
) COMMENT='文書';

COMMIT;
