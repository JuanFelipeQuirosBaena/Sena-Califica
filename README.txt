Sistema Administrativo SENA - Paquete completo
Instrucciones rápidas:
1. Coloca la carpeta 'sistema_administrativo' en tu carpeta de servidor (htdocs para XAMPP).
2. Importa 'sistema_administrativo.sql' en phpMyAdmin (esto crea la base y tablas).
3. Ajusta 'config/database.php' si tu usuario/clave MySQL son diferentes.
4. Abre en el navegador: http://localhost/sistema_administraxtivo_sena/login.php
   Credenciales: admin@sena.com / 1234

Notas:
- El password del admin en el SQL está en texto plano 1234 para facilitar pruebas. Puedes cambiarlo manualmente luego con password_hash() si deseas seguridad.
- Todos los formularios tienen validación HTML5 y JS básica, y validación en PHP.
