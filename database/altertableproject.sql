ALTER TABLE projects
ADD team_id INT NULL AFTER created_by;

ALTER TABLE projects
ADD CONSTRAINT fk_projects_team
FOREIGN KEY (team_id) REFERENCES teams(id)
ON DELETE SET NULL;
