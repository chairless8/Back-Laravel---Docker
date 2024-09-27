Libreta de Direcciones Avanzada - Backend
Este es el backend de la aplicación web Libreta de Direcciones Avanzada, desarrollada con Laravel 11 y PHP 8.2. La aplicación permite gestionar contactos con información adicional y realizar búsquedas avanzadas.

Tecnologías Utilizadas
Framework: Laravel 11
Lenguaje: PHP 8.2
Base de Datos: MySQL
Contenedores: Docker y Docker Compose
Requisitos Previos
Docker y Docker Compose instalados en tu sistema.
Instalación y Ejecución con Docker
Clonar el Repositorio
bash
Copy code
git clone https://github.com/tu_usuario/tu_repositorio.git
cd tu_repositorio
Configuración Inicial
Copia el archivo de variables de entorno y genera la clave de la aplicación:

bash
Copy code
cp .env.example .env
docker-compose run --rm app php artisan key:generate
Construir y Levantar los Contenedores
bash
Copy code
docker-compose up -d --build
Esto levantará los servicios definidos en docker-compose.yml:

app: Contenedor de la aplicación Laravel.
db: Contenedor de la base de datos MySQL.
Ejecutar Migraciones y Seeders
Para crear las tablas y poblar la base de datos con datos ficticios:

bash
Copy code
docker-compose exec app php artisan migrate --seed
Nota: Este comando ejecuta las migraciones y seeders dentro del contenedor app.

Uso de la API
La API expone endpoints para gestionar contactos y sus detalles.

Endpoints Principales
GET /api/contacts: Obtener lista de contactos.
GET /api/contacts/{id}: Obtener un contacto específico.
POST /api/contacts: Crear un nuevo contacto.
PUT /api/contacts/{id}: Actualizar un contacto existente.
DELETE /api/contacts/{id}: Eliminar un contacto.
Búsqueda Avanzada
GET /api/contacts?search={valor}: Buscar contactos por múltiples atributos.
Notas Adicionales
La aplicación sigue principios SOLID y utiliza patrones de diseño como Repositorios.
Asegúrate de que los puertos configurados en docker-compose.yml no entren en conflicto con otros servicios en tu máquina.
Los datos de acceso a la base de datos están definidos en el archivo .env.
Contacto
Si tienes preguntas o necesitas asistencia, por favor contactme al correo jesen842@gmail.com