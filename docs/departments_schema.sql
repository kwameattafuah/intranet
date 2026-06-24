-- ================================================================
-- GACL Intranet — Departments, Forms, Policies Schema
-- ================================================================

CREATE TABLE IF NOT EXISTS `gacl_departments` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `slug`        VARCHAR(50) NOT NULL UNIQUE,
  `name`        VARCHAR(200) NOT NULL,
  `short_name`  VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `icon`        VARCHAR(50) DEFAULT 'business',
  `color`       VARCHAR(20) DEFAULT '#1a3a5c',
  `head_name`   VARCHAR(200) DEFAULT NULL,
  `head_title`  VARCHAR(200) DEFAULT NULL,
  `email`       VARCHAR(200) DEFAULT NULL,
  `phone`       VARCHAR(50) DEFAULT NULL,
  `ext`         VARCHAR(20) DEFAULT NULL,
  `location`    VARCHAR(200) DEFAULT NULL,
  `active`      TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gacl_dept_sections` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `dept_id`     INT(11) NOT NULL,
  `name`        VARCHAR(200) NOT NULL,
  `head_name`   VARCHAR(200) DEFAULT NULL,
  `head_title`  VARCHAR(200) DEFAULT NULL,
  `ext`         VARCHAR(50) DEFAULT NULL,
  `sort_order`  INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dept_files` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `dept_id`     INT(11) NOT NULL,
  `title`       VARCHAR(300) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `filename`    VARCHAR(300) NOT NULL,
  `file_type`   VARCHAR(20) DEFAULT 'pdf',
  `file_size`   VARCHAR(20) DEFAULT NULL,
  `category`    VARCHAR(100) DEFAULT 'General',
  `uploaded_by` VARCHAR(200) DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `downloads`   INT(11) NOT NULL DEFAULT 0,
  `active`      TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dept_forms` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `dept_id`     INT(11) DEFAULT NULL COMMENT 'NULL = general/all depts',
  `title`       VARCHAR(300) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `filename`    VARCHAR(300) NOT NULL,
  `file_type`   VARCHAR(20) DEFAULT 'pdf',
  `category`    VARCHAR(100) DEFAULT 'General',
  `version`     VARCHAR(20) DEFAULT 'v1.0',
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `downloads`   INT(11) NOT NULL DEFAULT 0,
  `active`      TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dept_contacts` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `dept_id`     INT(11) NOT NULL,
  `name`        VARCHAR(200) NOT NULL,
  `title`       VARCHAR(200) DEFAULT NULL,
  `ext`         VARCHAR(20) DEFAULT NULL,
  `phone`       VARCHAR(50) DEFAULT NULL,
  `email`       VARCHAR(200) DEFAULT NULL,
  `is_head`     TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`  INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dept_announcements` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `dept_id`     INT(11) DEFAULT NULL COMMENT 'NULL = company-wide',
  `title`       VARCHAR(300) NOT NULL,
  `body`        TEXT NOT NULL,
  `priority`    ENUM('normal','important','urgent') NOT NULL DEFAULT 'normal',
  `posted_by`   VARCHAR(200) DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at`  DATE DEFAULT NULL,
  `active`      TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gacl_policies` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(300) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `filename`    VARCHAR(300) NOT NULL,
  `category`    VARCHAR(100) DEFAULT 'General',
  `dept_id`     INT(11) DEFAULT NULL,
  `version`     VARCHAR(20) DEFAULT 'v1.0',
  `effective_date` DATE DEFAULT NULL,
  `review_date` DATE DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `downloads`   INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gacl_airports` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `code`        VARCHAR(5) NOT NULL UNIQUE,
  `name`        VARCHAR(200) NOT NULL,
  `city`        VARCHAR(100) NOT NULL,
  `region`      VARCHAR(100) DEFAULT NULL,
  `manager`     VARCHAR(200) DEFAULT NULL,
  `phone`       VARCHAR(50) DEFAULT NULL,
  `email`       VARCHAR(200) DEFAULT NULL,
  `ext`         VARCHAR(20) DEFAULT NULL,
  `runways`     INT(11) DEFAULT 1,
  `description` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ================================================================
-- Seed departments (15 GACL departments)
-- ================================================================
INSERT IGNORE INTO `gacl_departments` (slug,name,short_name,description,icon,color,head_title,ext,location) VALUES
('finance','Finance & Administration','Finance','Manages the company\'s financial resources including treasury, accounts payable/receivable, payroll, stores and the forex bureau.','account_balance','#1a3a5c','Group Executive (Finance)','2218','Admin Block, 1st Floor'),
('hcos','Human Capital & Office Services','HCOS','Responsible for talent management, recruitment, learning & development, office services and employee welfare.','people','#f0a500','Group Executive (HC & OS)','2222','Admin Block, 2nd Floor'),
('ict','Information & Communications Technology','ICT','Manages IT infrastructure, applications, security, data centre operations and technology support across all airports.','computer','#1565c0','Ag. Director, ICT','2263','ICT Block'),
('operations','Airport Operations','Ops','Oversees all airside operations, terminal operations, customer service, safety & environment and rescue & firefighting services.','flight','#27ae60','Group Executive (AM)','2476','Operations Centre'),
('avsec','Aviation Security','AVSEC','Responsible for all aviation security operations, intelligence, quality control, training and cargo security.','verified_user','#37474f','Director, AVSEC','2295','Security Control Centre'),
('commercial','Commercial Services','Commercial','Drives commercial growth through business development, retail and concessions, cargo management and properties.','trending_up','#6a1b9a','Director, Commercial','2409','Admin Block, 3rd Floor'),
('corporate-comms','Corporate Communications & PR','Corp Comms','Manages all corporate communications, public relations, branding and stakeholder engagement.','record_voice_over','#00838f','Head, Corporate Comms','6111','Admin Block, Ground Floor'),
('facilities','Facilities & Infrastructure Management','Facilities','Manages buildings, grounds, electromechanical systems, transport, housekeeping and the facilities management call centre.','build','#6d4c41','Director, Facilities','2211','Engineering Workshop'),
('legal','Legal Services & Company Secretariat','Legal','Provides legal advisory services, manages company secretariat functions, regulatory compliance and contract management.','gavel','#1a3a5c','Director, Legal','2297','Admin Block, 4th Floor'),
('procurement','Procurement','Procurement','Handles all procurement activities for goods, services and works across the organisation.','shopping_cart','#e65100','Group Executive (Procurement)','6178','Admin Block, 2nd Floor'),
('strategy','Strategy & Corporate Performance','Strategy','Drives corporate strategy, research, analytics and performance management across GACL.','show_chart','#4a148c','Manager, Strategy','2326','Admin Block, 1st Floor'),
('audit','Internal Audit, Compliance & Risk','Audit & Risk','Provides independent assurance on internal controls, risk management and regulatory compliance.','security','#c0392b','Director, Audit','2294','Admin Block, 3rd Floor'),
('airport-planning','Airport Planning & Projects','Planning','Manages all capital projects including civil, structural, architectural and quantity surveying works.','architecture','#00695c','Director, Planning','2298','Projects Office'),
('business-dev','Business Development','Biz Dev','Identifies and develops new revenue streams, route development and strategic partnerships.','business_center','#1565c0','Head, Business Development','6170','Commercial Block'),
('mds-office','MD\'s Directorate','MD Office','The Office of the Managing Director and Deputy Managing Director overseeing all company operations.','star','#1a3a5c','Managing Director','2212','Executive Block');

-- ================================================================
-- Seed department contacts from Internal Directory
-- ================================================================
-- Finance
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='finance'),'Group Executive (Finance)','Group Executive',  '2218',1,1),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Executive Assistant','Executive Assistant','2330',0,2),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Treasurer','Treasurer','2221',0,3),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Financial Accountant','Financial Accountant','2607',0,4),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Accounts Payable','Accounts Payable','6141 / 2341 / 2019',0,5),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Accounts Receivable','Accounts Receivable','2333',0,6),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Payroll','Payroll','2450',0,7),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Cash Office','Cash Office','2605',0,8),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Fixed Assets','Fixed Assets','2403',0,9),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Forex Bureau','Forex Bureau','4317',0,10),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Credit Control / Investment','Credit Control / Investment','2337',0,11),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Stationery Stores','Stationery Stores','2535 / 6150 / 6151',0,12);

-- HCOS
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Group Executive (HC & OS)','Group Executive','2222',1,1),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Executive Assistant','Executive Assistant','2240',0,2),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Operations Manager','Operations Manager','2272',0,3),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Manager, Talent Management','Manager, Talent Management','2223',0,4),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Asst. Manager, Recruitment & Reward','Asst. Manager, Recruitment, Reward & Benefit','2408',0,5),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Officer, Human Capital','Officer, Human Capital','2460',0,6),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Asst. Manager, Office Services','Asst. Manager, Office Services','6127',0,7),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Admin Officer (Office Services)','Admin Officer, Office Services','2508',0,8),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Manager, L&D Centre','Manager, L&D Centre','2340',0,9),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Asst. Manager, L&D Centre','Asst. Manager, L&D Centre','6140',0,10),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Asst. Manager, Performance','Asst. Manager, Performance','2293',0,11);

-- ICT
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='ict'),'Ag. Director, ICT','Ag. Director','2263',1,1),
((SELECT id FROM gacl_departments WHERE slug='ict'),'ICT Secretariat','Secretariat','6109',0,2),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Main Office','Main Office','2321',0,3),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Manager, IT Security','Manager, IT Security','2548',0,4),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Manager, IT Operations (Kamel)','Manager, IT Operations','6106',0,5),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Manager, IT Applications (Aaron)','Manager, IT Applications','6107',0,6),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Manager, IT Applications (Kwame)','Manager, IT Applications','6108',0,7),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Data Centre (Main Office)','Data Centre','2630',0,8),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Software Officer (Zack)','Software Officer','6110',0,9),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Hardware Officer (Emma)','Hardware Officer','6125',0,10),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Data Centre Monitoring Room','Monitoring Room','6101 / 6103 / 6104',0,11);

-- Airport Operations
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='operations'),'Group Executive (AM)','Group Executive (Airport Management)','2476',1,1),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Executive Assistant','Executive Assistant','6180',0,2),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Head KIA (Mr Ahilijah)','Head, KIA','6142',0,3),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Safety Manager','Safety Manager','6161',0,4),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Safety Main Office','Safety Office','6183',0,5),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Customer Service Manager','Customer Service Manager','3383',0,6),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Customer Service Main Office','Customer Service','6116 / 6156',0,7),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Manager, Airside Operations','Manager, Airside Ops','2468',0,8),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Airside Ops Office','Airside Ops Office','2344',0,9),
((SELECT id FROM gacl_departments WHERE slug='operations'),'OP\'S Room','Operations Room','2319 / 2446',0,10),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Fire Station (Watch Room)','Fire Station','2505',0,11),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Chief Fire Officer','Chief Fire Officer','6009',0,12),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Manager, Rescue & Fire Fighting','Manager, RFFS','6004',0,13);

-- AVSEC
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='avsec'),'Director, AVSEC','Director','2295',1,1),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'Personal Assistant','Personal Assistant','2316',0,2),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Administration Officer','Administration Officer','2576',0,3),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Operations Manager','Operations Manager','2334',0,4),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Quality Control Manager','Quality Control Manager','2317',0,5),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'Quality Control Main Office','Quality Control','5071 / 5072',0,6),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'Manager, Intelligence/Investigation','Manager, Intell/Investigation','2504',0,7),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'Intel/Investigation Main Office','Intel/Investigation','5074 / 5037',0,8),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'Assistant AVSEC Manager','Assistant AVSEC Manager','2269',0,9),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Training Manager','Training Manager','2412',0,10),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Training Office','Training Office','2434',0,11),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'Central Screening','Central Screening','2291',0,12),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'CCTV Room','CCTV Room','2292',0,13);

-- Commercial Services
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Director, Commercial Services','Director','2409',1,1),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Personal Assistant','Personal Assistant','2242',0,2),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Manager, Commercial & Retail','Manager, Commercial & Retail','2456',0,3),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Asst. Manager (Adverts)','Asst. Manager, Adverts','6147',0,4),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Asst. Manager (Retail)','Asst. Manager, Retail','2299',0,5),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Asst. Manager (Car Park)','Asst. Manager, Car Park','6146',0,6),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Head, Business Development','Head, Business Development','6170',0,7),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Manager, Properties','Manager, Properties','2328',0,8),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Asst. Manager, Real Estate','Asst. Manager, Real Estate','6145',0,9),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Asst. Manager, Properties (Edem)','Asst. Manager, Properties','6176',0,10),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Cargo Operations, Asst. Manager','Asst. Manager, Cargo','6177',0,11),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Cargo Main Office','Cargo Office','6160',0,12);

-- Corporate Comms
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='corporate-comms'),'Head, Corporate Comms & PR','Head','6111',1,1),
((SELECT id FROM gacl_departments WHERE slug='corporate-comms'),'Main Office','Main Office','2501 / 6162',0,2);

-- Facilities
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Director, Facilities','Director','2211',1,1),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Personal Assistant','Personal Assistant','2213',0,2),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Facilities Management Call Centre','FMCC','3401 / 3402',0,3),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Manager, Buildings & Grounds','Manager, Buildings & Grounds','2035',0,4),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Asst. Manager (Housekeeping)','Asst. Manager, Housekeeping','5035',0,5),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Asst. Manager (Assets)','Asst. Manager, Assets','6166',0,6),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Manager, Transport','Manager, Transport','2570',0,7),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Transport Main Office','Transport Office','2375',0,8),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Manager, Electromechanical','Manager, Electromechanical','2432',0,9),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Asst. Managers (HVAC)','Asst. Managers, HVAC','5075',0,10),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Sewage Treatment Plant','STP','2500 / 2325',0,11),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Electrical Manager','Electrical Manager','2437',0,12);

-- Legal
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='legal'),'Director, Legal','Director','2297',1,1),
((SELECT id FROM gacl_departments WHERE slug='legal'),'Manager','Manager','2478',0,2),
((SELECT id FROM gacl_departments WHERE slug='legal'),'Assistant Manager','Assistant Manager','6171',0,3),
((SELECT id FROM gacl_departments WHERE slug='legal'),'Main Office','Main Office','2488',0,4);

-- Procurement
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Group Executive (Procurement)','Group Executive','6178',1,1),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Executive Assistant','Executive Assistant','2632',0,2),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Manager, Goods & Services (Kwamena)','Manager, Goods & Services','2016',0,3),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Manager, Services (Prince)','Manager, Services','2405',0,4),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Asst. Manager, Works (Daniel)','Asst. Manager, Works','6119',0,5),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Asst. Manager, Services (Wisdom)','Asst. Manager, Services','6118',0,6),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Asst. Manager, Goods (Eric)','Asst. Manager, Goods','6126',0,7),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Procurement Goods','Procurement (Goods)','6154 / 6155',0,8),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Procurement Goods & Works','Procurement (Goods & Works)','6144',0,9);

-- Strategy
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='strategy'),'Manager, Strategy','Manager','2326',1,1),
((SELECT id FROM gacl_departments WHERE slug='strategy'),'Research Analysis Manager','Research Analysis Manager','6192',0,2),
((SELECT id FROM gacl_departments WHERE slug='strategy'),'Main Office','Main Office','6114 / 2226',0,3);

-- Audit
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='audit'),'Director, Internal Audit','Director','2294',1,1),
((SELECT id FROM gacl_departments WHERE slug='audit'),'Manager','Manager','2482',0,2),
((SELECT id FROM gacl_departments WHERE slug='audit'),'Main Office','Main Office','2528 / 2378 / 3222',0,3);

-- Airport Planning
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Director, Airport Planning','Director','2298',1,1),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Personal Assistant','Personal Assistant','2017',0,2),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Project Manager, Structure (G. Apenkwah)','Project Manager, Structure','2431',0,3),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Project Manager, Civil (E. Fynn)','Project Manager, Civil','2207',0,4),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Project Manager, Civil (Kwasi B)','Project Manager, Civil','2034',0,5),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Architect (B. Koko)','Architect','2032',0,6),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Project Manager, Architecture (Rexford)','Project Manager, Architecture','6132',0,7),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Projects Manager (Gifty)','Projects Manager','2355',0,8),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Project Manager, Quantities (Isaac Quarm)','Project Manager, Quantities','6136',0,9),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Project Manager, Quantities (Mankata)','Project Manager, Quantities','6133',0,10),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Civil Engineers','Civil Engineers','2033 / 6134',0,11),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Draughtsman','Draughtsman','6138',0,12);

-- MD's Office
INSERT IGNORE INTO `dept_contacts` (dept_id,name,title,ext,is_head,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Managing Director','Managing Director','2212',1,1),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Executive Assistant (MD)','Executive Assistant','2248',0,2),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Deputy Managing Director','DMD','2277',0,3),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Executive Assistant (DMD)','Executive Assistant','6179',0,4),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Reception','Reception','2568',0,5),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Reception (T2 Extension)','T2 Reception','6169',0,6),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Conference Room (HQ)','Conference Room, HQ','2394',0,7),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Conference Room (L&D Centre)','Conference Room, LDC','6117',0,8),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Executive Reception','Executive Reception','2037',0,9),
((SELECT id FROM gacl_departments WHERE slug='mds-office'),'Registry','Registry','6159 / 2397',0,10);

-- ================================================================
-- Seed sections
-- ================================================================
INSERT IGNORE INTO `gacl_dept_sections` (dept_id,name,sort_order) VALUES
((SELECT id FROM gacl_departments WHERE slug='finance'),'Financial Accounting',1),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Management Accounting',2),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Treasury & Investment',3),
((SELECT id FROM gacl_departments WHERE slug='finance'),'Stores',4),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Human Capital Operations',1),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Talent Management',2),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Learning & Development',3),
((SELECT id FROM gacl_departments WHERE slug='hcos'),'Office Services',4),
((SELECT id FROM gacl_departments WHERE slug='ict'),'IT Operations',1),
((SELECT id FROM gacl_departments WHERE slug='ict'),'IT Applications',2),
((SELECT id FROM gacl_departments WHERE slug='ict'),'IT Security',3),
((SELECT id FROM gacl_departments WHERE slug='ict'),'Hardware & Infrastructure',4),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Airside Operations',1),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Terminal Operations',2),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Customer Service',3),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Safety & Environment',4),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Rescue & Fire Fighting Services',5),
((SELECT id FROM gacl_departments WHERE slug='operations'),'Regional Airports',6),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Operations',1),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Quality Control',2),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Intelligence & Investigation',3),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Training',4),
((SELECT id FROM gacl_departments WHERE slug='avsec'),'AVSEC Cargo Security',5),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Commercial & Retail',1),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Properties & Real Estate',2),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Cargo Management',3),
((SELECT id FROM gacl_departments WHERE slug='commercial'),'Business Development',4),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Buildings, Pavements & Grounds',1),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Electromechanical',2),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Transport',3),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Electricals',4),
((SELECT id FROM gacl_departments WHERE slug='facilities'),'Facilities Management Call Centre',5),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Architecture',1),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Civil Engineering',2),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Projects Management',3),
((SELECT id FROM gacl_departments WHERE slug='airport-planning'),'Quantity Surveying',4),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Planning & Sourcing',1),
((SELECT id FROM gacl_departments WHERE slug='procurement'),'Sourcing Services',2),
((SELECT id FROM gacl_departments WHERE slug='audit'),'Internal Audit',1),
((SELECT id FROM gacl_departments WHERE slug='audit'),'Control & Compliance',2),
((SELECT id FROM gacl_departments WHERE slug='strategy'),'Corporate Strategy',1),
((SELECT id FROM gacl_departments WHERE slug='strategy'),'Research & Analytics',2),
((SELECT id FROM gacl_departments WHERE slug='legal'),'Legal Services',1),
((SELECT id FROM gacl_departments WHERE slug='legal'),'Advisory',2),
((SELECT id FROM gacl_departments WHERE slug='legal'),'Company Secretariat',3);

-- ================================================================
-- Seed airports (with real contact data)
-- ================================================================
INSERT IGNORE INTO `gacl_airports` (code,name,city,region,phone,ext,runways,description) VALUES
('ACC','Kotoka International Airport','Accra','Greater Accra','030 2550612','2568',2,'Ghana\'s primary international gateway, handling the majority of international and domestic flights. Home to GACL headquarters.'),
('KMS','Kumasi Airport','Kumasi','Ashanti','032 2022969','6200',1,'Serves the Ashanti Region and acts as a hub for domestic travel to northern Ghana and international charter flights.'),
('TML','Tamale Airport','Tamale','Northern','037 2091367','6300',1,'The main gateway to northern Ghana, supporting domestic routes and humanitarian operations in the region.'),
('TKD','Takoradi Airport','Takoradi','Western','','',1,'Serves the Western Region, supporting oil and gas industry operations and domestic travel to Ghana\'s oil capital.'),
('NYI','Sunyani Airport','Sunyani','Bono','035 2023316','6400',1,'Serves the Bono Region and supports domestic connectivity to central Ghana.'),
('HZA','Ho Airport','Ho','Volta','','5063',1,'Serves the Volta Region, providing domestic air access to the eastern corridor of Ghana.');
