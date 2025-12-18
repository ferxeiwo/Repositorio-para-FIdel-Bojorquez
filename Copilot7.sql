-- ==========================================
--  TABLA USUARIO
-- ==========================================
CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);

-- ==========================================
--  TABLA PERSONA
-- ==========================================
CREATE TABLE persona (
    id_persona SERIAL PRIMARY KEY,
    id_usuario INTEGER UNIQUE REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    telefono VARCHAR(20)
);

-- ==========================================
--  TABLA ROL
-- ==========================================
CREATE TABLE rol (
    id_rol SERIAL PRIMARY KEY,
    nombre_rol VARCHAR(100) NOT NULL
);

-- ==========================================
--  TABLA USUARIO_ROL (relación muchos a muchos)
-- ==========================================
CREATE TABLE usuario_rol (
    id_usuario INTEGER REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    id_rol INTEGER REFERENCES rol(id_rol) ON DELETE CASCADE,
    PRIMARY KEY (id_usuario, id_rol)
);

-- ==========================================
--  TABLA PERMISOS
-- ==========================================
CREATE TABLE permisos (
    id_permiso SERIAL PRIMARY KEY,
    id_rol INTEGER REFERENCES rol(id_rol) ON DELETE CASCADE,
    nombre_permiso VARCHAR(150) NOT NULL
);

-- ==========================================
--  TABLA ACCESOS (bitácora de login/logout)
-- ==========================================
CREATE TABLE accesos (
    id_bitacora SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    fecha_acceso TIMESTAMP NOT NULL,
    fecha_desconexion TIMESTAMP,
    estado_login VARCHAR(50)
);

-- ==========================================
--  TABLA AUTENTICACION (códigos temporales, 2FA, etc.)
-- ==========================================
CREATE TABLE autenticacion (
    id_autenticacion SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    codigo VARCHAR(100),
    fecha_creacion TIMESTAMP NOT NULL,
    duracion INTEGER
);

-- ==========================================
--  TABLA RECUPERACION_CONTRASENA (tokens de recuperación)
-- ==========================================
CREATE TABLE recuperacion_contrasena (
    id_token SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    token VARCHAR(255),
    fecha_expiracion TIMESTAMP NOT NULL
);

-- ==========================================
--  CONSULTA DE USUARIOS SOSPECHOSOS
-- ==========================================
SELECT 
    u.id_usuario,
    u.nombre_usuario,
    p.nombres,
    p.apellidos,
    a.fecha_acceso,
    a.fecha_desconexion,
    a.estado_login,
    au.duracion AS duracion_autenticacion,
    r.nombre_rol,
    pe.nombre_permiso
FROM accesos a
INNER JOIN usuario u ON a.id_usuario = u.id_usuario
LEFT JOIN persona p ON p.id_usuario = u.id_usuario
LEFT JOIN autenticacion au ON au.id_usuario = u.id_usuario
LEFT JOIN usuario_rol ur ON ur.id_usuario = u.id_usuario
LEFT JOIN rol r ON ur.id_rol = r.id_rol
LEFT JOIN permisos pe ON pe.id_rol = r.id_rol
WHERE a.estado_login = 'sospechoso';
