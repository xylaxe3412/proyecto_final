# Configuración de Google OAuth para Motiveo

## Pasos para configurar Google OAuth:

### 1. Crear credenciales en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Habilita la **Google+ API** y **Google Identity API**
4. Ve a **Credenciales** > **Crear credenciales** > **ID de cliente de OAuth 2.0**
5. Configura la pantalla de consentimiento OAuth si es necesario
6. Selecciona **Aplicación web** como tipo de aplicación

### 2. Configurar URLs de redirección

En la configuración del cliente OAuth, agrega estas URLs autorizadas:

**Orígenes de JavaScript autorizados:**
```
http://localhost:8000
http://127.0.0.1:8000
```

**URIs de redirección autorizadas:**
```
http://localhost:8000/auth/google/callback
http://127.0.0.1:8000/auth/google/callback
```

### 3. Obtener credenciales

Después de crear el cliente OAuth, obtendrás:
- **Client ID** (ID de cliente)
- **Client Secret** (Secreto de cliente)

### 4. Configurar variables de entorno

1. Copia el archivo `.env.example` a `.env`:
   ```bash
   cp .env.example .env
   ```

2. Edita el archivo `.env` y agrega tus credenciales de Google:
   ```env
   GOOGLE_CLIENT_ID=tu_client_id_aqui
   GOOGLE_CLIENT_SECRET=tu_client_secret_aqui
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

### 5. Ejecutar migraciones

Asegúrate de que las migraciones estén ejecutadas para tener los campos de Google en la tabla users:

```bash
php artisan migrate
```

### 6. Probar la funcionalidad

1. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

2. Ve a la página de login: `http://localhost:8000/login`
3. Haz clic en el botón "Continuar con Google"
4. Completa el flujo de autenticación de Google

## Funcionalidad implementada:

✅ **Login con Google** - Los usuarios pueden iniciar sesión con su cuenta de Google
✅ **Registro automático** - Si el usuario no existe, se crea automáticamente
✅ **Vinculación de cuentas** - Si ya existe un usuario con el mismo email, se vincula la cuenta de Google
✅ **Manejo de errores** - Mensajes de error informativos en caso de fallos
✅ **UI moderna** - Botón de Google integrado en el diseño existente

## Campos de usuario agregados:

- `google_id`: ID único del usuario en Google
- `avatar`: URL del avatar del usuario desde Google
- `email_verified_at`: Se marca automáticamente como verificado para usuarios de Google

## Seguridad:

- Los usuarios de Google reciben una contraseña aleatoria para mayor seguridad
- Se validan todas las respuestas de Google antes de crear/autenticar usuarios
- Manejo robusto de errores para evitar exposición de información sensible
