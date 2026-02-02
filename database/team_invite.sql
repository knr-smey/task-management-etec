CREATE TABLE team_invites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  team_id INT NOT NULL,
  token CHAR(64) NOT NULL UNIQUE,
  expires_at DATETIME NULL,
  max_uses INT NOT NULL DEFAULT 50,
  used_count INT NOT NULL DEFAULT 0,
  created_by INT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  INDEX (team_id),
  INDEX (token)
);


ALTER TABLE team_members
  ADD UNIQUE KEY uniq_team_member (team_id, member_id),
  ADD INDEX idx_member (member_id),
  ADD INDEX idx_team (team_id);
