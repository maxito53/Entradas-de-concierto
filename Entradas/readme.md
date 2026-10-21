# Ticketmaxter

Aplicación para generar entradas de conciertos

## Características

- Generación de entradas en formato PDF con código de barras y código QR
- Implementación de Doctrine 3.3 para ORM
- Utilización de mPDF para la generación de PDFs
- Métodos PHP hechos con la declaración más nueva

## Requisitos

- PHP 8.1 o superior
- MySQL 5.7 o superior
- Composer

## Proceso de creación

1. **Crear la base de datos:**

```sql
CREATE DATABASE TuraMax_Entradas;
```

2. **Generar las tablas a partir de las entidades (desde el directorio raíz):**

```bash
php bin/doctrine.php orm:schema-tool:create
```

3. **Carga de datos inicial:**

El propio `index.php` se encarga de cargar los datos en la base de datos (en el caso de que aún no estén) a la que se ejecute el archivo.

4. **Acceder desde el navegador:**

### Generar PDF de una entrada

```
http://servidor/index.php?ref=CODIGO_ENTRADA
```

> Este tiene contraseña de seguridad y hay 20 entradas de prueba que se pueden consultar (de `ENT-2025-00001` a `ENT-2025-00020`).

### Generar XML con eventos de una fecha

```
http://tu-servidor/index.php?data=YYYY-MM-DD
```

> Especificando una fecha en formato `YYYY-MM-DD` devuelve un XML con los eventos programados de ese dia. Un dia que tiene eventos es el `2025-05-10`.

> Hay ocasiones donde el XML o la imagen de QR o BarCode no me han cargado y me a salido como que fallaron, pero al recargar ya salen.

## Apuntes a tener en cuenta

### Instalación de librerías necesarias

Para instalar mPDF con Doctrine 3.3 y la versión 8.3.6 tuve que instalar la versión 8.2.5 de mPDF y la 1.2 de QRCode con Composer

```bash
composer require mpdf/mpdf
composer require mpdf/qrcode
```

Tuve que instalar de igual forma symfony/cache ya que según el error de pantalla mPDF la necesaitaba para su correcto funcionamiento:

```bash
composer require symfony/cache
```
### Extensiones necesarias de PHP

Otras dependencias que me hizo instalar el mPDF de PHP (que no tenía ya instaladas):

```bash
sudo apt install php8.3-gd
sudo apt install php8.3-mbstring
```

### Permisos y carpetas temporales

También le tuve que crear la carpeta temporal y modificarle los permisos

```bash
mkdir -p /var/www/html/Entradas/vendor/mpdf/mpdf/tmp/mpdf
sudo chown -R www-data:www-data /var/www/html/Entradas/vendor/mpdf/mpdf/tmp
sudo chmod -R 775 /var/www/html/Entradas/vendor/mpdf/mpdf/tmp
```

> No se en caso de borrar el vendor y volverlo a crear si esto podría influir a que después no vaya, de igual forma Eclipse me muestra un error en las declaraciones de las clases y namespaces de `Mpdf` y `Qrcode`, aunque funciona perfectamente, creo que es un error de mi Eclipse que se quedó buggueado pero por si acaso lo comento.