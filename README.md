# 🧩 API Laravel - Login sin contraseña (OTP vía WhatsApp con Twilio)

Este proyecto es una **API REST en Laravel** que permite autenticación sin contraseña.  
El usuario recibe un código **OTP (One-Time Password)** por **WhatsApp** usando **Twilio**, el cual se valida para iniciar sesión.

---

## 🚀 Requisitos previos

Antes de comenzar, asegúrate de tener instalado:

- [PHP 8.2 o superior](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [PostgreSQL o MySQL](https://www.postgresql.org/download/)
- [XAMPP / Laragon / Valet / Docker] (para servidor local)
- [Twilio Account](https://www.twilio.com/try-twilio) con acceso al sandbox de WhatsApp

---

## 📦 Instalación del proyecto

1️⃣ **Clonar el repositorio**

```bash
git clone https://github.com/tuusuario/tu-repo.git
cd tu-repo
```

2️⃣ **Instalar dependencias de Laravel**

```bash
composer install
```

3️⃣ **Copiar archivo de entorno y generar clave**

```bash
cp .env.example .env
php artisan key:generate
```

4️⃣ **Configurar base de datos en `.env`**

Ejemplo usando PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=jn_pruebas
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```

5️⃣ **Configurar Twilio (para WhatsApp)**

Agrega tus credenciales reales de Twilio:

```env
TWILIO_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_TOKEN=yyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
TWILIO_WHATSAPP_FROM=+14155238886
```

> ⚠️ Recuerda **activar el sandbox de WhatsApp** en Twilio y unir tu número enviando el mensaje indicado (por ejemplo, `join sunset-sky`) al **+1 415 523 8886**.

---

## 🗄️ Migraciones y datos iniciales

Ejecuta las migraciones para crear las tablas:

```bash
php artisan migrate
```

Si tu módulo `Auth` tiene migraciones personalizadas:

```bash
php artisan migrate --path=modules/Auth/Database/Migrations
```

(O bien refresca todo:)
```bash
php artisan migrate:refresh
```

---

## 🔑 Endpoints principales

### 1️⃣ Solicitar código OTP
**POST** `/api/auth/request-code`

```json
{
  "phone_number": "+5215555555555"
}
```

✅ Respuesta esperada:
```json
{
  "message": "Código enviado correctamente"
}
```

---

### 2️⃣ Verificar código OTP
**POST** `/api/auth/verify-code`

```json
{
  "phone_number": "+5215555555555",
  "code": "123456"
}
```

✅ Respuesta esperada:
```json
{
  "token": "1|xyzabc123..."
}
```

---

## 🧠 Estructura modular

El proyecto usa una arquitectura basada en **módulos** dentro de la carpeta `modules/`.

```
modules/
└── Auth/
    ├── Http/
    │   └── Controllers/
    │       └── AuthController.php
    ├── Models/
    │   └── OtpCode.php
    ├── Database/
    │   └── Migrations/
    │       └── 2025_10_20_000000_create_otp_codes_table.php
    └── Routes/
        └── api.php
```

Esto facilita mantener funcionalidades independientes por módulo (Auth, Users, Orders, etc).

---

## 🧪 Probar con Postman

Importa tu colección o haz una prueba manual:

**1️⃣ Solicitar código**
```
POST http://apis.pruebazerotwo.com/api/auth/request-code
```

**2️⃣ Verificar código**
```
POST http://apis.pruebazerotwo.com/api/auth/verify-code
```

---

## 🧰 Comandos útiles

| Descripción | Comando |
|--------------|----------|
| Instalar dependencias | `composer install` |
| Generar clave de app | `php artisan key:generate` |
| Ejecutar migraciones | `php artisan migrate` |
| Refrescar base de datos | `php artisan migrate:refresh` |
| Limpiar cachés | `php artisan optimize:clear` |
| Iniciar servidor local | `php artisan serve --host=apis.pruebazerotwo.com --port=8000` |

---

## 🧑‍💻 Autor

**Jesús Nava**  
📧 contacto: *tuemail@dominio.com*  
🔗 GitHub: [@tuusuario](https://github.com/tuusuario)

---

## 📜 Licencia

Este proyecto está bajo la licencia **MIT** — puedes usarlo, modificarlo y distribuirlo libremente.

---

## 🧩 Créditos

- [Laravel](https://laravel.com/)
- [Twilio API](https://www.twilio.com/)
- [PostgreSQL](https://www.postgresql.org/)
