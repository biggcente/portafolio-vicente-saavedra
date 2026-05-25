## 3. Áreas Donde Se Utilizó la IA

### 3.1 Diseño Visual y Estilos CSS

Se utilizó Claude para definir la paleta de colores, tipografía y estilo visual general del portafolio. La IA propuso un diseño oscuro moderno con los siguientes elementos:

- **Paleta de colores:** fondo oscuro (`#0a0a0f`), acento violeta (`#7c5cfc`) y variantes  
- **Tipografía:** *Space Grotesk* para texto general y *JetBrains Mono* para elementos de código  
- **Sistema de variables CSS:** uso de `--bg`, `--accent`, `--text` y derivadas  
- **Componentes:** `skill-cards`, `tech-bars`, `project-cards` y `nav` con efecto *glassmorphism*  
- **Diseño responsive:** uso de Bootstrap 5 como base con sobreescritura de estilos  

---

### 3.2 Estructura de la Base de Datos

Claude asistió en el diseño del esquema relacional de la base de datos MySQL. Las tablas generadas fueron:

- **usuarios:** almacenamiento seguro de credenciales con hash *bcrypt*  
- **biografia:** datos personales y profesionales del estudiante  
- **habilidades:** herramientas con nombre, ícono y orden de visualización  
- **tecnologias:** lenguajes y frameworks con nivel de dominio (0–100)  
- **proyectos:** título, descripción, imagen, `demo_url` y `github_url`  
- **mensajes:** formulario de contacto con estado de lectura  

---

### 3.3 Backend PHP

Se utilizó IA para generar la estructura de archivos PHP del proyecto, incluyendo:

- Conexión a base de datos usando PDO con manejo de excepciones  
- Sistema de autenticación con sesiones PHP y `password_verify()`  
- Endpoints AJAX en `api.php` para login y formulario de contacto  

---

### 3.4 JavaScript y AJAX

Claude generó el código JavaScript para:

- Animación de barras de progreso con `IntersectionObserver`  
- Cambio de opacidad del navbar al hacer scroll  
- Envío del formulario de login de forma asíncrona con `fetch()`  

