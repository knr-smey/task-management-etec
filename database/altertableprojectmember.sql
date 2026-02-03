ALTER TABLE project_members
ADD UNIQUE KEY uq_project_user (project_id, user_id);
