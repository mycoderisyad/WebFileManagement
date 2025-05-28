-- Create the database
CREATE DATABASE IF NOT EXISTS `file_management`;

-- Use the database
USE `file_management`;

-- Create files table
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL,
  `upload_date` datetime NOT NULL,
  `deadline` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories (optional)
INSERT INTO `files` (`title`, `description`, `category`, `filename`, `file_path`, `file_type`, `file_size`, `upload_date`, `deadline`) VALUES
('Sample PDF Document', 'This is a sample PDF document for testing purposes.', 'Tutorial', 'sample.pdf', 'uploads/sample.pdf', 'application/pdf', 52428, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY)),
('Project Proposal', 'Initial project proposal document for the semester project.', 'Project', 'proposal.docx', 'uploads/proposal.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 35840, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY)),
('Meeting Notes', 'Notes from the weekly team meeting.', 'Meeting', 'notes.docx', 'uploads/notes.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 24576, NOW(), NULL); 