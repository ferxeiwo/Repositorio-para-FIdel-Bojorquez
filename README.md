Sistema de Renta y Venta de Bicicletas

1. Introducción

El presente proyecto consiste en el desarrollo de un sistema web para la gestión de renta y venta de bicicletas. El sistema fue diseñado con el objetivo de administrar de forma eficiente el inventario de bicicletas, permitiendo a los usuarios visualizar los modelos disponibles, seleccionar la cantidad deseada y realizar operaciones de renta o compra. Asimismo, se busca evitar la duplicación visual de productos, mostrando un solo registro por modelo.

---

2. Resumen del Sistema

El sistema permite gestionar bicicletas almacenadas individualmente en una base de datos, pero presentadas al usuario de manera agrupada por modelo. Cada modelo muestra su precio de renta, precio de venta, descuento (si aplica) y la cantidad total disponible. El usuario puede elegir si desea rentar o comprar y seleccionar la cantidad requerida, siempre validando la disponibilidad.

---

3. Requisitos

a. Requisitos Funcionales

* Registrar bicicletas en el sistema con sus respectivos atributos.
* Mostrar bicicletas disponibles agrupadas por modelo.
* Permitir la renta de bicicletas por un número determinado de días.
* Calcular automáticamente el costo total de la renta.
* Permitir la venta de bicicletas.
* Controlar la disponibilidad del inventario.
* Confirmar rentas y actualizar el estado de las bicicletas.
* Permitir la devolución de bicicletas.

Requisitos No Funcionales

* Interfaz clara y fácil de usar.
* Respuesta rápida del sistema.
* Código organizado y mantenible.
* Correcta validación de datos de entrada.

b. Requisitos Técnicos

* Lenguaje PHP.
* Base de datos MySQL.
* Servidor web Apache.
* Uso de PDO para la conexión a la base de datos.
* HTML5 y CSS3 para la interfaz.
* Navegador web moderno.

---

4. Casos de Uso

a. Diagramas

Los diagramas de casos de uso representan las interacciones entre los usuarios y el sistema, incluyendo acciones como agregar bicicletas, rentar, comprar y devolver bicicletas.

b. Descripción de Casos de Uso

* **Agregar bicicleta:** El administrador registra bicicletas con sus datos y cantidad.
* **Ver bicicletas:** El usuario visualiza los modelos disponibles.
* **Rentar bicicleta:** El usuario selecciona un modelo, cantidad y días de renta.
* **Confirmar renta:** El sistema valida disponibilidad y actualiza el inventario.
* **Devolver bicicleta:** Se restablece la disponibilidad.
* **Comprar bicicleta:** Se descuenta del inventario la cantidad comprada.

---

5. Entidades, Atributos y Relaciones

Entidad principal: Bicicleta

**Atributos:**

* id_bicicleta
* tipo
* precio_renta
* precio_venta
* descuento
* disponibilidad
* estado_mantenimiento
* imagen

**Relaciones y Cardinalidad:**

* Un modelo de bicicleta puede existir en varias unidades.
* Cada bicicleta pertenece a un solo modelo.
* La relación es de uno a muchos (1:N).

El Diagrama Entidad-Relación muestra cómo las bicicletas se almacenan individualmente pero se agrupan lógicamente por tipo.

---

6. Arquitectura del Sistema

El sistema sigue una arquitectura cliente-servidor:

* El cliente accede mediante un navegador web.
* El servidor procesa la lógica en PHP.
* La base de datos MySQL almacena la información.
* La comunicación se realiza mediante consultas SQL usando PDO.

---

7. Diseño de Interfaz (Figma)

La interfaz fue diseñada siguiendo principios de usabilidad y simplicidad. Se priorizó una navegación clara, una barra de menú accesible y tarjetas de productos que muestren la información relevante de cada modelo. El diseño puede representarse mediante prototipos elaborados en Figma.

---

8. Estructura del Proyecto

```
/config
  database.php

/bicicletas
  agregar_bici.php

/pedido
  elegir_accion.php
  rentar.php
  confirmar_renta.php
  devolver.php

/auth
  login.php

/uploads
stylemenu.css
menu.php
```

---

9. Instalación y Configuración

1. Instalar un servidor local (XAMPP, WAMP o Laragon).
2. Colocar el proyecto en la carpeta del servidor.
3. Crear la base de datos en MySQL.
4. Importar las tablas necesarias.
5. Configurar la conexión en `config/database.php`.
6. Acceder al sistema desde el navegador.

---

10. Uso y Operación del Sistema

El usuario accede al menú principal donde puede visualizar las bicicletas disponibles. Desde allí puede seleccionar un modelo, elegir si desea rentar o comprar, indicar la cantidad y confirmar la operación. El sistema se encarga de validar la disponibilidad y actualizar el inventario.

---

11. Base de Datos (Modelado)

La base de datos está diseñada para almacenar bicicletas de forma individual, lo que permite un control preciso del inventario. Sin embargo, el sistema agrupa los registros por modelo al mostrarlos, facilitando la experiencia del usuario y evitando duplicaciones visuales.

---

12. Conclusión

El sistema desarrollado cumple con los objetivos planteados, permitiendo una gestión eficiente de la renta y venta de bicicletas. La correcta separación entre almacenamiento y visualización garantiza un mejor control del inventario y una experiencia de usuario clara. El proyecto demuestra la aplicación práctica de conceptos de bases de datos, programación web y diseño de interfaces.
