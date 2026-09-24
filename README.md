Agenda Telefónica (phone-book)
==============================

Developer: Óscar Pérez (www.oscarperez.es)
Tested up to: 6.6
Stable tag: 2.0.0
License: GPLv2 or later

== Descripción ==

Plugin de WordPress para gestionar y mostrar una agenda telefónica
(nombre + teléfono).

- Panel de administración con alta, edición y borrado de teléfonos.
- Shortcode `[phonebook]` para mostrar la agenda en cualquier página o entrada,
  con enlaces `tel:` que permiten llamar desde el móvil.
- Cada registro admite varios números (uno por línea en el formulario); cada
  número se muestra en su propia línea con su enlace `tel:`.
- Atributo opcional `columnas` (1 a 4, por defecto 1):
  `[phonebook columnas="2"]`.

== Estructura de ficheros ==

- `phone-book.php` — fichero principal del plugin.
- `uninstall.php` — elimina la tabla de base de datos al desinstalar el plugin.
- `includes/db.php` — acceso a base de datos (creación de tabla vía `dbDelta`, CRUD).
- `includes/admin.php` — menú, formulario y listado de administración.
- `includes/public.php` — shortcode y carga de assets públicos.
- `includes/views/` — plantillas PHP usadas por admin.php y public.php.
- `assets/css/`, `assets/js/` — estilos y scripts de administración y públicos.

== Base de datos ==

Tabla `{prefijo}opg_plugin_phonebook` (se mantiene el nombre histórico para no
perder datos de instalaciones anteriores):

- `idPhone` INT, autoincremental.
- `name` VARCHAR(255).
- `phone` VARCHAR(255) (uno o varios números separados por saltos de línea).

== Menú compartido ==

Este plugin cuelga del menú compartido "Oscar Pérez Plugins" (`opg_plugins`),
junto con los plugins hermanos (weblinks, corporación municipal). Si el plugin
maestro (weblinks) está activo, se añade como submenú suyo; si no, crea el menú
compartido él mismo.

En versiones anteriores el plugin vivía bajo un menú propio "Ayuntamiento
Plugins"; desde la 2.0.0 se integra en el menú común de la familia.

== Instalación ==

1. Copia la carpeta `phone-book` en `wp-content/plugins`.
2. Activa el plugin desde el panel de WordPress.
3. Ve a "Oscar Pérez Plugins → Agenda telefónica" y añade los teléfonos.
4. Inserta el shortcode `[phonebook]` donde quieras mostrar la agenda.

== Changelog ==

= 2.0.0 =
Reescritura completa del plugin:
- Nuevo listado público mediante el shortcode `[phonebook]` con enlaces `tel:`
  (el plugin antes solo tenía backend de administración).
- Varios números por registro: el formulario usa ahora un área de texto (un
  número por línea) en lugar del antiguo campo donde había que teclear `<br>`
  a mano. La columna `phone` se amplía de VARCHAR(40) a VARCHAR(255). Los datos
  antiguos con `<br>` se siguen interpretando, sin necesidad de migración.
- Reestructuración en ficheros `includes/`, `includes/views/` y `assets/`.
- Corregidas vulnerabilidades: inyección SQL en la consulta por id (ahora con
  `$wpdb->prepare`), falta de nonces/CSRF en el formulario y en el borrado, y
  falta de sanitizado/escapado de entradas y salidas.
- Los scripts y estilos ya no se cargan en todo el sitio: los de administración
  solo en la pantalla del plugin, y los públicos solo cuando se usa el
  shortcode. Se elimina la carga inútil del cargador de medios (media-upload /
  thickbox), que el plugin nunca usaba. Las rutas usan `plugin_dir_url()` en
  lugar de `WP_PLUGIN_URL.'/opg_phonebook/'`.
- El plugin se integra ahora en el menú compartido "Oscar Pérez Plugins"
  (antes tenía su propio menú "Ayuntamiento Plugins").
- Creación de tabla mediante `dbDelta` (actualiza instalaciones existentes sin
  perder datos) y borrado de la tabla movido a `uninstall.php` (antes se
  ejecutaba un `DROP TABLE` que, además, apuntaba a desinstalación pero se
  registró históricamente con un typo).
- Los iconos de modificar/borrar usan ahora dashicons de WordPress en lugar de
  imágenes propias.

= 1.1.0 = *Release Date - 2nd January, 2015
En el listado de teléfonos se cambian los literales "Modificar" y "Borrar" por
dos imágenes. Antes de eliminar el registro se pide confirmación con un
`confirm` de JavaScript.

= 1.0.0 = *Release Date - 23rd November, 2014
Primera versión operativa.
