CREATE TABLE team_sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  team_id INT NOT NULL,

  day_of_week ENUM(
    'mon','tue','wed','thu','fri','sat','sun'
  ) NOT NULL,

  start_time TIME NOT NULL,
  end_time TIME NOT NULL,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (team_id)
    REFERENCES teams(id)
    ON DELETE CASCADE
);

DROP TABLE IF EXISTS team_members;
CREATE TABLE team_members (
  team_id INT NOT NULL,
  member_id INT NOT NULL,
  joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (team_id, member_id),

  CONSTRAINT fk_team_members_team
    FOREIGN KEY (team_id) REFERENCES teams(id)
    ON DELETE CASCADE,

  CONSTRAINT fk_team_members_user
    FOREIGN KEY (member_id) REFERENCES users(id)
    ON DELETE CASCADE
);
