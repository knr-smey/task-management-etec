/* =====================================================
   TASK MANAGEMENT SYSTEM - INITIAL SEEDER
   =====================================================

   HOW TO RUN THIS FILE (CMD / TERMINAL):

   1) Make sure MySQL is installed:
      mysql --version

   2) Make sure MySQL service is running.

   3) Run this seeder:
      mysql -u root -p task_management_db < database/seed.sql

   4) Login after seeding:
      Email:    superadmin@etec.com
      Password: kruteachsolution2026
      Role:     super_admin

   NOTE:
   - Safe to run multiple times
   - Will NOT create duplicate users or roles
   - Roles table MUST already exist
===================================================== */


/* ===============================
   SUPER ADMIN USER
   =============================== */
INSERT INTO users (name, email, password_hash, is_active, created_at)
SELECT
  'Super Admin',
  'superadmin@etec.com',
  '$2y$10$XWwqtyRxovFzGDl29se1gu82ps8BAnrOLbtc4I6QTRyGOqiz3lONa',
  1,
  NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM users WHERE email = 'superadmin@etec.com'
);


/* ===============================
   ASSIGN SUPER_ADMIN ROLE
   =============================== */
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u
JOIN roles r ON r.name = 'super_admin'
WHERE u.email = 'superadmin@etec.com'
AND NOT EXISTS (
  SELECT 1
  FROM user_roles ur
  WHERE ur.user_id = u.id
    AND ur.role_id = r.id
);


/* ===============================
   VERIFY (OPTIONAL)
   =============================== */
-- SELECT u.id, u.name, u.email, r.name AS role
-- FROM users u
-- JOIN user_roles ur ON ur.user_id = u.id
-- JOIN roles r ON r.id = ur.role_id
-- WHERE u.email = 'superadmin@etec.com';
