CREATE TABLE IF NOT EXISTS `livros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(40) DEFAULT NULL,
  `editora` varchar(40) DEFAULT NULL,
  `edicao` int DEFAULT NULL,
  `anoPublicacao` varchar(4) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `ativo` int NOT NULL DEFAULT '1',
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `assuntos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `livro_id` int DEFAULT NULL,
  `descricao` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livro_id` (`livro_id`) USING BTREE,
  CONSTRAINT `assuntos_ibfk_1` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `autores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `livro_id` int DEFAULT NULL,
  `nome` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livro_id` (`livro_id`) USING BTREE,
  CONSTRAINT `autores_ibfk_1` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`) ON DELETE CASCADE
);

DROP VIEW IF EXISTS relatorio_livros;

CREATE VIEW relatorio_livros AS
SELECT 
  l.id,
  l.titulo,
  l.editora,
  l.edicao,
  l.anoPublicacao,
  l.preco,
  l.ativo,
  GROUP_CONCAT(DISTINCT a.nome SEPARATOR ', ') AS autores,
  GROUP_CONCAT(DISTINCT s.descricao SEPARATOR ', ') AS assuntos
FROM 
  livros l
  LEFT JOIN autores a ON a.livro_id = l.id
  LEFT JOIN assuntos s ON s.livro_id = l.id
WHERE 
  l.deletedAt IS NULL
GROUP BY
  l.id;


-- inserts
INSERT INTO `livros` (`titulo`, `editora`, `edicao`, `anoPublicacao`, `preco`, `ativo`, `createdAt`, `updatedAt`, `deletedAt`)
VALUES
('Clean Code', 'Prentice Hall', 1, '2008', 45.90, 1, NOW(), NOW(), NULL),
('Design Patterns', 'Addison-Wesley', 1, '1994', 60.50, 1, NOW(), NOW(), NULL),
('The Pragmatic Programmer', 'Addison-Wesley', 2, '1999', 50.75, 1, NOW(), NOW(), NULL),
('Refactoring', 'Addison-Wesley', 2, '1999', 55.00, 1, NOW(), NOW(), NULL),
('Domain-Driven Design', 'Addison-Wesley', 1, '2003', 70.00, 1, NOW(), NOW(), NULL),
('The Secrets of the Universe', 'Orion Publishing', 1, '2021', 49.99, 1, NOW(), NOW(), NULL),
('Mastering PHP 8', 'TechPress', 2, '2023', 69.50, 1, NOW(), NOW(), NULL),
('Introduction to Quantum Computing', 'FutureTech', 1, '2020', 59.99, 1, NOW(), NOW(), NULL),
('The Art of War for Developers', 'CodeCraft', 1, '2019', 39.90, 1, NOW(), NOW(), NULL),
('Data Structures with Python', 'Learning House', 3, '2022', 89.99, 1, NOW(), NOW(), NULL),
('Modern Web Design', 'WebWorld', 2, '2022', 74.80, 1, NOW(), NOW(), NULL),
('Artificial Intelligence for Beginners', 'FutureMind', 1, '2023', 99.50, 1, NOW(), NOW(), NULL),
('Building Scalable APIs', 'API Mastery', 1, '2022', 109.99, 1, NOW(), NOW(), NULL),
('The Journey of JavaScript', 'CodePress', 4, '2021', 79.99, 1, NOW(), NOW(), NULL),
('Exploring Blockchain Technology', 'Blockchain World', 1, '2023', 199.00, 1, NOW(), NOW(), NULL);

INSERT INTO `autores` (`livro_id`, `nome`)
VALUES
(1, 'Robert C. Martin'), 
(2, 'Erich Gamma'),
(2, 'Richard Helm'),
(3, 'Andrew Hunt'),
(3, 'David Thomas'),
(4, 'Martin Fowler'),    
(5, 'Eric Evans'),
(6, 'John Doe'),
(6, 'Jane Smith'),
(7, 'Richard Roe'),
(8, 'Alice Johnson'),
(8, 'David Clark'),
(9, 'Sarah Thompson'),
(9, 'Brian Lee'),
(10, 'Michael White'),
(11, 'Sophia Green'),
(12, 'Oliver Brown'),
(13, 'Emily Davis'),
(14, 'Daniel Taylor'),
(15, 'James Wilson'),
(15, 'Jessica Harris');


INSERT INTO `assuntos` (`livro_id`, `descricao`)
VALUES
(1, 'Clean Code Principle'),
(2, 'Software Arch'),
(2, 'Object-Oriented'),
(3, 'Software Develop'),
(4, 'Refactoring Tech.'),
(5, 'DDD Concepts'),
(6, 'Science'),
(7, 'Space Exploration'),
(8, 'Programming'),
(9, 'PHP'),
(10, 'Quantum Computing'),
(11, 'Technology'),
(12, 'Strategy'),
(13, 'Software Developme'),
(14, 'Data Structures'),
(15, 'Python'),
(6, 'Web Design'),
(7, 'Artificial Intellig'),
(8, 'APIs'),
(9, 'JavaScript'),
(10, 'Blockchain');