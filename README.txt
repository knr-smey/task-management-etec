NORMAL PHP TASK TEMPLATE (No Framework)

Upload to byte.host:
- Upload EVERYTHING in this zip into htdocs (public root).
- If your host allows folders outside htdocs:
  Move /config and /logs outside, then update index.php require paths.

Demo login:
- email: admin@example.com
- password: admin123

URLs:
- /login
- /dashboard
- /tasks

Next steps:
- Replace demo session auth with real DB auth (see config/db.php).
- Implement roles using includes/role.php.
