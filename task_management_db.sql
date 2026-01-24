-- =========================================================
-- Task Management System (Normalized 3NF)
-- Database: task_management_db
-- Compatible with MySQL / MariaDB (ByetHost)
-- =========================================================

SET NAMES utf8mb4;
SET time_zone = "+00:00";

-- ---------------------------------------------------------
-- Create & use database
-- ---------------------------------------------------------
CREATE DATABASE IF NOT EXISTS task_management_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE task_management_db;

-- ---------------------------------------------------------
-- Drop tables (safe order)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS issues;
DROP TABLE IF EXISTS issue_statuses;
DROP TABLE IF EXISTS task_assignees;
DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS task_statuses;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS team_members;
DROP TABLE IF EXISTS teams;
DROP TABLE IF EXISTS user_roles;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;

-- ---------------------------------------------------------
-- 1) Users & Roles
-- ---------------------------------------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  course VARCHAR(255),
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_roles (
  user_id INT NOT NULL,
  role_id INT NOT NULL,
  PRIMARY KEY (user_id, role_id),
  CONSTRAINT fk_user_roles_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_user_roles_role
    FOREIGN KEY (role_id) REFERENCES roles(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- 2) Teams & Projects
-- ---------------------------------------------------------
CREATE TABLE teams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  team_type VARCHAR(20) NOT NULL,
  created_by INT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_teams_created_by (created_by),
  CONSTRAINT fk_teams_created_by
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE team_members (
  team_id INT NOT NULL,
  user_id INT NOT NULL,
  team_role VARCHAR(20) NOT NULL,
  joined_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (team_id, user_id),
  INDEX idx_team_members_user (user_id),
  CONSTRAINT fk_team_members_team
    FOREIGN KEY (team_id) REFERENCES teams(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_team_members_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  team_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  status VARCHAR(20) NOT NULL DEFAULT 'active',
  created_by INT NOT NULL,
  start_date DATE NULL,
  end_date DATE NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_projects_team (team_id),
  INDEX idx_projects_created_by (created_by),
  CONSTRAINT fk_projects_team
    FOREIGN KEY (team_id) REFERENCES teams(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_projects_created_by
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- 3) Tasks
-- ---------------------------------------------------------
CREATE TABLE task_statuses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  status_id INT NOT NULL,
  priority VARCHAR(10) NOT NULL DEFAULT 'medium',
  estimate_hours DECIMAL(6,2),
  due_date DATE,
  created_by INT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  INDEX idx_tasks_project (project_id),
  INDEX idx_tasks_status (status_id),
  INDEX idx_tasks_created_by (created_by),
  CONSTRAINT fk_tasks_project
    FOREIGN KEY (project_id) REFERENCES projects(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_tasks_status
    FOREIGN KEY (status_id) REFERENCES task_statuses(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_tasks_created_by
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE task_assignees (
  task_id INT NOT NULL,
  user_id INT NOT NULL,
  assigned_by INT NOT NULL,
  assigned_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (task_id, user_id),
  INDEX idx_task_assignees_user (user_id),
  CONSTRAINT fk_task_assignees_task
    FOREIGN KEY (task_id) REFERENCES tasks(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_task_assignees_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_task_assignees_assigned_by
    FOREIGN KEY (assigned_by) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- 4) Issues
-- ---------------------------------------------------------
CREATE TABLE issue_statuses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE issues (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  task_id INT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  status_id INT NOT NULL,
  created_by INT NOT NULL,
  assigned_to INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  INDEX idx_issues_project (project_id),
  INDEX idx_issues_task (task_id),
  INDEX idx_issues_status (status_id),
  CONSTRAINT fk_issues_project
    FOREIGN KEY (project_id) REFERENCES projects(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_issues_task
    FOREIGN KEY (task_id) REFERENCES tasks(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_issues_status
    FOREIGN KEY (status_id) REFERENCES issue_statuses(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_issues_created_by
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_issues_assigned_to
    FOREIGN KEY (assigned_to) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- 5) Activity Logs
-- ---------------------------------------------------------
CREATE TABLE activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  actor_user_id INT NOT NULL,
  entity_type VARCHAR(20) NOT NULL,
  entity_id INT NOT NULL,
  action VARCHAR(80) NOT NULL,
  old_value JSON,
  new_value JSON,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_logs_actor (actor_user_id),
  INDEX idx_logs_entity (entity_type, entity_id),
  CONSTRAINT fk_activity_logs_actor
    FOREIGN KEY (actor_user_id) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Seed Data
-- ---------------------------------------------------------
INSERT INTO roles (name) VALUES
('super_admin'),
('admin'),
('instructor'),
('member');

INSERT INTO task_statuses (name) VALUES
('todo'),
('in_progress'),
('in_review'),
('done');

INSERT INTO issue_statuses (name) VALUES
('open'),
('triaged'),
('in_progress'),
('resolved'),
('closed');

-- DONE