# MICROSERVICIOS DE ENTRADAS

Este proyecto crea una API tipo REST para poder gestionar mediante rutas todas las entidades de la creación de entradas.

## PROBLEMAS 

Mientras creaba el proyecto tuve un problema con la carpeta media, que cuando intentaba el programa acceder a ella para guardar una imagen no dejaba, al final le tuve que modificar los permisos ejecutando en la raíz del proyecto:

```bash
sudo chmod -R 775 media
sudo chown -R www-data:www-data media
```

## RUTAS API

A continuación listo todas las rutas API que tiene el proyecto, tener en cuenta que estas rutas están pensadas si el proyecto se encuentra en la carpeta base de Apache.

Todo este recopilatorio de rutas lo hice a prisa, así que capaz hay cosas mal, otro sitio donde tengo las rutas y probadas es en el Postman:
https://gold-firefly-407045.postman.co/workspace/My-Workspace~63d2ee99-541b-4243-83c0-037b6d1ffa00/collection/42588559-62fcc3d1-1eb1-4544-a213-b16cd554127a?action=share&creator=42588559 

### HACER AUTH Y GENERAR TOKEN

Todas las rutas de esta API (a excepción de esta) necesitan de autorización para usarse, para esto a las rutas hay que proporcionarles un Token, este token se obtiene haciendo login dando el `email` y `password` de algún cliente registrado en la tabla `CLIENTE`, por ejemplo, en la siguiente ruta se hace login con el registro del cliente Juan Garcia.

Para ejecutar esta URL con el parámetro `entity` como `auth` hay que hacerle un `POST`:

```bash
http://localhost/Microservicios/src/index.php?entity=auth
```

Y añadir en el Body (raw JSON):

```json
{
    "email": "juan.garcia@ejemplo.com", 
    "password": "password"
}
```

Esta consulta devolverá en la respuesta un Token que es el que hay que poner en las consultas como `Authorization` en `Bearer Token` en Postman.

### RUTAS DE CLIENTE 

#### **`GET` GET ALL**
Devuelve todos los registros de Cliente.

Params:
- ``entity``: cliente

```bash
http://localhost/Microservicios/src/index.php?entity=cliente
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Cliente que coicida con el ID proporcionado.

Params:
- ``entity``: cliente
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=cliente&id=1
```

#### **`POST` CREATE**
Crea un registro Cliente.

Params:
- ``entity``: cliente

```bash
http://localhost/Microservicios/src/index.php?entity=cliente
```

Body (raw JSON):

```json
{
  "dni": "3333333A",
  "nombre": "Max",
  "apellidos": "Tura Aguilera",
  "email": "mtaguil6@gmail.com",
  "telefono": "600088469",
  "password": "password"
}
```

#### **`PUT` UPDATE**
Actualiza un registro Cliente especificado por su ID.

Params:
- ``entity``: cliente
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=cliente&id=1
```

Body (raw JSON):

```json
{
    "email": "mtaguil5@gmail.com"
}
```

#### **`DELETE` DELETE**
Elimina un registro Cliente especificado por su ID.

Params:
- ``entity``: cliente
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=cliente&id=1
```

### RUTAS DE ARTISTA 

#### **`GET` GET ALL**
Devuelve todos los registros de Artista.

Params:
- ``entity``: artista

```bash
http://localhost/Microservicios/src/index.php?entity=artista
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Artista que coicida con el ID proporcionado.

Params:
- ``entity``: artista
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=artista&id=1
```

#### **`POST` CREATE**
Crea un registro Artista.

Params:
- ``entity``: artista

```bash
http://localhost/Microservicios/src/index.php?entity=artista
```

Body (raw JSON):

```json
{
  "nombre": "Kyle Alessandro",
  "pais": "Noruega",
  "genero": "Pop"
}
```

#### **`PUT` UPDATE**
Actualiza un registro Artista especificado por su ID.

Params:
- ``entity``: artista
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=artista&id=11
```

Body (raw JSON):

```json
{
    "pais": "España"
}
```

#### **`DELETE` DELETE**
Elimina un registro Artista especificado por su ID.

Params:
- ``entity``: artista
- ``id``: 11

```bash
http://localhost/Microservicios/src/index.php?entity=artista&id=11
```

### RUTAS DE LUGAR 

#### **`GET` GET ALL**
Devuelve todos los registros de Lugar.

Params:
- ``entity``: lugar

```bash
http://localhost/Microservicios/src/index.php?entity=lugar
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Lugar que coicida con el ID proporcionado.

Params:
- ``entity``: lugar
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=lugar&id=1
```

#### **`POST` CREATE**
Crea un registro Lugar.

Params:
- ``entity``: lugar

```bash
http://localhost/Microservicios/src/index.php?entity=lugar
```

Body (raw JSON):

```json
{
  "nombre": "Palacio de los Deportes",
  "direccion": "Av. del Parque, 123",
  "ciudad": "Madrid",
  "capacidad": 15000
}
```

#### **`PUT` UPDATE**
Actualiza un registro Lugar especificado por su ID.

Params:
- ``entity``: lugar
- ``id``: 13

```bash
http://localhost/Microservicios/src/index.php?entity=lugar&id=13
```

Body (raw JSON):

```json
{
  "capacidad": 18000,
  "direccion": "Avenida del Parque, 123"
}
```

#### **`DELETE` DELETE**
Elimina un registro Lugar especificado por su ID.

Params:
- ``entity``: lugar
- ``id``: 13

```bash
http://localhost/Microservicios/src/index.php?entity=lugar&id=13
```

### RUTAS DE CONCIERTO 

#### **`GET` GET ALL**
Devuelve todos los registros de Concierto.

Params:
- ``entity``: concierto

```bash
http://localhost/Microservicios/src/index.php?entity=concierto
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Concierto que coicida con el ID proporcionado.

Params:
- ``entity``: concierto
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=concierto&id=1
```

#### **`POST` CREATE**
Crea un registro Concierto.

Params:
- ``entity``: concierto

```bash
http://localhost/Microservicios/src/index.php?entity=concierto
```

Body (form-data):

- nombre `Text`: Cocnierto de Verano
- descripcion `Text`: Un gran espectáculo para el verano
- artista_id `Text`: 1
- imagen `File`: `ARCHIVO IMAGEN`

#### **`PUT` UPDATE**
Actualiza un registro Concierto especificado por su ID.

Params:
- ``entity``: concierto
- ``id``: 15

```bash
http://localhost/Microservicios/src/index.php?entity=concierto&id=15
```

Body (form-data):

- nombre `Text`: Cocnierto de Verano actualizado
- descripcion `Text`: Un gran espectáculo para el verano actualizado
- artista_id `Text`: 1
- imagen `File`: `ARCHIVO IMAGEN`

Me ha dado error de que pille la imagen pero no los campos de texto, en caso de pasar eso se puede pasar la iamgen en `form-data` el resto en JSON por `raw`:

```json
{
    "nombre": "Cocnierto de Verano actualizado",
    "descripcion": "Un gran espectáculo para el verano actualizado"
}
```

#### **`DELETE` DELETE**
Elimina un registro Concierto especificado por su ID.

Params:
- ``entity``: concierto
- ``id``: 13

```bash
http://localhost/Microservicios/src/index.php?entity=concierto&id=15
```

### RUTAS DE ZONA 

#### **`GET` GET ALL**
Devuelve todos los registros de Zona.

Params:
- ``entity``: zona

```bash
http://localhost/Microservicios/src/index.php?entity=zona
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Zona que coicida con el ID proporcionado.

Params:
- ``entity``: zona
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=zona&id=1
```

#### **`POST` CREATE**
Crea un registro Zona.

Params:
- ``entity``: zona

```bash
http://localhost/Microservicios/src/index.php?entity=zona
```

Body (raw JSON):

```json
{
    "nombre": "Prueba",
    "descripcion": "Zona de prueba pues para probar"
}
```

#### **`PUT` UPDATE**
Actualiza un registro Zona especificado por su ID.

Params:
- ``entity``: zona
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=zona&id=1
```

Body (raw JSON):

```json
{
    "descripcion": "Zona de prueba actualizada pues para probar"
}
```

#### **`DELETE` DELETE**
Elimina un registro Zona especificado por su ID.

Params:
- ``entity``: zona
- ``id``: 9

```bash
http://localhost/Microservicios/src/index.php?entity=zona&id=9
```

### RUTAS DE SESION 

#### **`GET` GET ALL**
Devuelve todos los registros de Sesion.

Params:
- ``entity``: sesion

```bash
http://localhost/Microservicios/src/index.php?entity=sesion
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Sesion que coicida con el ID proporcionado.

Params:
- ``entity``: sesion
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=sesion&id=1
```

#### **`POST` CREATE**
Crea un registro Sesion.

Params:
- ``entity``: sesion

```bash
http://localhost/Microservicios/src/index.php?entity=sesion
```

Body (raw JSON):

```json
{
    "concierto_id": 1,
    "lugar_id": 2,
    "fecha": "2025-06-15",
    "hora": "20:00:00",
    "estado": "PROGRAMADO"
}
```

#### **`PUT` UPDATE**
Actualiza un registro Sesion especificado por su ID.

Params:
- ``entity``: sesion
- ``id``: 32

```bash
http://localhost/Microservicios/src/index.php?entity=sesion&id=32
```

Body (raw JSON):

```json
{
    "fecha": "2025-06-16",
    "hora": "21:00:00",
    "estado": "POSPUESTO"
}
```

#### **`DELETE` DELETE**
Elimina un registro Sesion especificado por su ID.

Params:
- ``entity``: sesion
- ``id``: 32

```bash
http://localhost/Microservicios/src/index.php?entity=sesion&id=32
```

#### **`GET` GET ONE BY CONCIERTO**
Devuelve un registro Sesion que coicida con el Concierto proporcionado.

Params:
- ``entity``: sesion
- ``action``: getSesionesByConcierto
- ``conciertoId``: 3

```bash
http://localhost/Microservicios/src/index.php?entity=sesion&action=getSesionesByConcierto&conciertoId=3
```

#### **`GET` GET ONE BY LUGAR**
Devuelve un registro Sesion que coicida con el Lugar proporcionado.

Params:
- ``entity``: sesion
- ``action``: getSesionesByLugar
- ``lugarId``: 2

```bash
http://localhost/Microservicios/src/index.php?entity=sesion&action=getSesionesByLugar&lugarId=2
```

#### **`GET` GET ONE BY FECHA**
Devuelve un registro Sesion que coicida con la fecha proporcionada.

Params:
- ``entity``: sesion
- ``action``: getSesionesByFecha
- ``fecha``: 2025-05-15

```bash
http://localhost/Microservicios/src/index.php?entity=sesion&action=getSesionesByFecha&fecha=2025-05-15
```

#### **`GET` GET ONE BY ESTADO**
Devuelve un registro Sesion que coicida con la estado proporcionado.

Params:
- ``entity``: sesion
- ``action``: getSesionesByEstado
- ``estado``: PROGRAMADO (solo puede ser PROGRAMADO, CANCELADO, COMPLETADO o POSPUESTO)

```bash
http://localhost/Microservicios/src/index.php?entity=sesion&action=getSesionesByEstado&estado=PROGRAMADO
```

### RUTAS DE PRECIO 

#### **`GET` GET ALL**
Devuelve todos los registros de Precio.

Params:
- ``entity``: precio

```bash
http://localhost/Microservicios/src/index.php?entity=precio
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Precio que coicida con el ID proporcionado.

Params:
- ``entity``: precio
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=precio&id=1
```

#### **`POST` CREATE**
Crea un registro Precio.

Params:
- ``entity``: precio

```bash
http://localhost/Microservicios/src/index.php?entity=precio
```

Body (raw JSON):

```json
{
    "sesion_id": 1,
    "zona_id": 2,
    "importe": 59.99
}
```

#### **`PUT` UPDATE**
Actualiza un registro Precio especificado por su ID.

Params:
- ``entity``: precio
- ``id``: 115

```bash
http://localhost/Microservicios/src/index.php?entity=precio&id=115
```

Body (raw JSON):

```json
{
    "importe": 69.99
}
```

#### **`DELETE` DELETE**
Elimina un registro Precio especificado por su ID.

Params:
- ``entity``: precio
- ``id``: 115

```bash
http://localhost/Microservicios/src/index.php?entity=precio&id=115
```

#### **`GET` GET ONE BY SESION**
Devuelve un registro Precio que coicida con la Sesion proporcionada.

Params:
- ``entity``: precio
- ``action``: getPreciosBySesion
- ``sesionId``: 5

```bash
http://localhost/Microservicios/src/index.php?entity=precio&action=getPreciosBySesion&sesionId=5
```

#### **`GET` GET ONE BY ZONA**
Devuelve un registro Precio que coicida con la Zona proporcionada.

Params:
- ``entity``: precio
- ``action``: getPreciosByZona
- ``zonaId``: 2

```bash
http://localhost/Microservicios/src/index.php?entity=precio&action=getPreciosByZona&zonaId=2
```

#### **`GET` GET ONE BY RANGO**
Devuelve un registro Precio que coicida con el rango proporcionado.

Params:
- ``entity``: precio
- ``action``: getPreciosByRango
- ``min``: 20.00
- ``max``: 50.00

```bash
http://localhost/Microservicios/src/index.php?entity=precio&action=getPreciosByRango&min=20.00&max=50.00
```

#### **`GET` GET ONE BY SESION AND ZONA**
Devuelve un registro Precio que coicida con la Sesion y Zona proporcionadas.

Params:
- ``entity``: precio
- ``action``: getPrecioBySesionAndZona
- ``sesionId``: 5
- ``zonaId``: 2

```bash
http://localhost/Microservicios/src/index.php?entity=precio&action=getPrecioBySesionAndZona&sesionId=5&zonaId=2
```

#### **`POST` SET MASSIVE PRECIO**
Introduce precios que se le pasan por JSON de forma masiva.

Params:
- ``entity``: precio
- ``action``: setMassivePrices
- ``sesionId``: 5

```bash
http://localhost/Microservicios/src/index.php?entity=precio&action=setMassivePrices&sesionId=5
```

Body (raw JSON):

```json
{
  "precios": [
    {
      "zona_id": 1,
      "importe": 25.00
    },
    {
      "zona_id": 2,
      "importe": 30.00
    },
    {
      "zona_id": 3,
      "importe": 35.00
    }
  ]
}
```

### RUTAS DE PAGO 

#### **`GET` GET ALL**
Devuelve todos los registros de Pago.

Params:
- ``entity``: pago

```bash
http://localhost/Microservicios/src/index.php?entity=pago
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Pago que coicida con el ID proporcionado.

Params:
- ``entity``: pago
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=pago&id=1
```

#### **`POST` CREATE**
Crea un registro Pago.

Params:
- ``entity``: pago

```bash
http://localhost/Microservicios/src/index.php?entity=pago
```

Body (raw JSON):

```json
{
  "cliente_id": 1,
  "fecha": "2025-05-21",
  "total": 125.50,
  "metodo": "Tarjeta",
  "referencia": "REF12345",
  "estado": "PENDIENTE"
}
```

#### **`PUT` UPDATE**
Actualiza un registro Pago especificado por su ID.

Params:
- ``entity``: pago
- ``id``: 11

```bash
http://localhost/Microservicios/src/index.php?entity=pago&id=11
```

Body (raw JSON):

```json
{
  "total": 150.75,
  "metodo": "Transferencia",
  "referencia": "TR98765",
  "estado": "EFECTUADO"
}
```

#### **`DELETE` DELETE**
Elimina un registro Pago especificado por su ID.

Params:
- ``entity``: pago
- ``id``: 11

```bash
http://localhost/Microservicios/src/index.php?entity=pago&id=11
```

#### **`GET` GET ONE BY CLIENTE**
Devuelve un registro Pago que coicida con el Cliente proporcionado.

Params:
- ``entity``: pago
- ``action``: getPagosByCliente
- ``clienteId``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=getPagosByCliente&clienteId=1
```

#### **`GET` GET ONE BY FECHAS**
Devuelve un registro Pago que coicida con las fechas proporcionadas.

Params:
- ``entity``: pago
- ``action``: getPagosByFecha
- ``fechaInicio``: 2025-01-01
- ``fechaFin``: 2025-05-21

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=getPagosByFecha&fechaInicio=2025-01-01&fechaFin=2025-05-21
```
#### **`GET` GET ONE BY ESTADO**
Devuelve un registro Pago que coicida con el Estado proporcionado.

Params:
- ``entity``: pago
- ``action``: getPagosByEstado
- ``estado``: EFECTUADO (solo EFECTUADO o PENDIENTE)

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=getPagosByEstado&estado=EFECTUADO
```

#### **`GET` GET ONE BY MÉTODO DE PAGO**
Devuelve un registro Pago que coicida con el método de pago proporcionado.

Params:
- ``entity``: pago
- ``action``: getPagosByMetodo
- ``estado``: TARJETA

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=getPagosByMetodo&metodo=TARJETA
```

#### **`GET` CAMBIAR ESTADO**
Cambia el estado del pago.

Params:
- ``entity``: pago
- ``action``: cambiarEstado
- ``id``: 1
- ``estado``: EFECTUADO

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=cambiarEstado&id=1&estado=EFECTUADO
```
#### **`GET` GET ONE BY PAGO**
Devuelve un registro Pago que coicida con el Pago proporcionado.

Params:
- ``entity``: pago
- ``action``: getEntradasByPago
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=getEntradasByPago&id=1
```
#### **`GET` GET ESTADÍSTICAS**
Devuelve un registro de estadísticas de los pagos.

Params:
- ``entity``: pago
- ``action``: getEstadisticas

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=getEstadisticas
```
#### **`GET` GET ESTADÍSTICAS BY FECHAS**
Devuelve un registro de estadísticas de los pagos acotados por fechas.

Params:
- ``entity``: pago
- ``action``: getEstadisticas
- ``fechaInicio``: 2025-01-01
- ``fechaFin``: 2025-05-21

```bash
http://localhost/Microservicios/src/index.php?entity=pago&action=getEstadisticas&fechaInicio=2025-01-01&fechaFin=2025-05-21
```

### RUTAS DE ASIENTO 

#### **`GET` GET ALL**
Devuelve todos los registros de Asiento.

Params:
- ``entity``: asiento

```bash
http://localhost/Microservicios/src/index.php?entity=asiento
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Asiento que coicida con el ID proporcionado.

Params:
- ``entity``: asiento
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&id=1
```

#### **`POST` CREATE**
Crea un registro Asiento.

Params:
- ``entity``: asiento

```bash
http://localhost/Microservicios/src/index.php?entity=asiento
```

Body (raw JSON):

```json
{
  "lugar_id": 1,
  "zona_id": 2,
  "fila": "A",
  "numero": "15",
  "estado": "DISPONIBLE"
}
```

#### **`PUT` UPDATE**
Actualiza un registro Asiento especificado por su ID.

Params:
- ``entity``: asiento
- ``id``: 118

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&id=118
```

Body (raw JSON):

```json
{
  "estado": "NO DISPONIBLE"
}
```

#### **`DELETE` DELETE**
Elimina un registro Asiento especificado por su ID.

Params:
- ``entity``: asiento
- ``id``: 118

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&id=118
```

#### **`GET` GET BY LUGAR**
Devuelve un registro Asiento que coicida con el Lugar proporcionado.

Params:
- ``entity``: asiento
- ``action``: getAsientosByLugar
- ``lugar_id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=getAsientosByLugar&lugar_id=1
```

#### **`GET` GET BY ZONA**
Devuelve un registro Asiento que coicida con la Zona proporcionada.

Params:
- ``entity``: asiento
- ``action``: getAsientosByZona
- ``zona_id``: 2

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=getAsientosByZona&zona_id=2
```

#### **`GET` GET BY ZONA AND ESTADO**
Devuelve un registro Asiento que coicida con la Zona y el Estado proporcionados.

Params:
- ``entity``: asiento
- ``action``: getAsientosByZonaAndEstado
- ``zona_id``: 2
- ``estado``: DISPONIBLE

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=getAsientosByZonaAndEstado&zona_id=2&estado=DISPONIBLE
```

#### **`GET` GET BY FILA**
Devuelve un registro Asiento que coicida con la fila proporcionada.

Params:
- ``entity``: asiento
- ``action``: getAsientosByFila
- ``lugar_id``: 1
- ``fila``: A

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=getAsientosByFila&lugar_id=1&fila=A
```

#### **`GET` GET BY FILA AND NÚMERO**
Devuelve un registro Asiento que coicida con la fila y el número proporcionados.

Params:
- ``entity``: asiento
- ``action``: getAsientoByFilaNumero
- ``lugar_id``: 1
- ``fila``: A
- ``numero``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=getAsientoByFilaNumero&lugar_id=1&fila=A&numero=1
```

#### **`GET` GET ENTRADAS BY ASIENTO**
Devuelve un registro Entrada que coicida con el asiento proporcionado.

Params:
- ``entity``: asiento
- ``action``: getEntradasByAsiento
- ``id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=getEntradasByAsiento&id=1
```

#### **`GET` GENERAR ESTRUCUTRA DE ASIENTOS**
Genera masivamente una estructura de asientos.

Params:
- ``entity``: asiento
- ``action``: generarEstructuraAsientos
- ``lugar_id``: 1
- ``zona_id``: 1
- ``filas``: 10
- ``asientosPorFila``: 20
- ``prefijo``: F

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=generarEstructuraAsientos&lugar_id=1&zona_id=1&filas=10&asientosPorFila=20&prefijo=F
```

#### **`GET` GET DISPONIBILIDAD DE ASIENTOS POR ZONA Y SESION**
Devuelve un registro Asiento que coicida con la disponibilidad de asientos de la zona y sesión proporcionados.

Params:
- ``entity``: asiento
- ``action``: getDisponibilidadAsientosPorZonaYSesion
- ``sesion_id``: 1
- ``zona_id``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=getDisponibilidadAsientosPorZonaYSesion&zona_id=1&sesion_id=1
```

#### **`GET` CAMBIAR ESTADO**
Cambia el estado del Asiento.

Params:
- ``entity``: asiento
- ``action``: cambiarEstado
- ``id``: 1
- ``estado``: NO%20DISPONIBLE (solo NO%20DISPONIBLE o DISPONIBLE)

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=cambiarEstado&id=1&estado=NO%20DISPONIBLE
```

#### **`POST` CREATE ASIENTOS**
Crea asientos de forma masiva

Params:
- ``entity``: asiento
- ``action``: crearMultiplesAsientos

```bash
http://localhost/Microservicios/src/index.php?entity=asiento&action=crearMultiplesAsientos
```

Body (raw JSON):

```json
{
  "asientos": [
    {
      "lugar_id": 1,
      "zona_id": 1,
      "fila": "A",
      "numero": "1",
      "estado": "DISPONIBLE"
    },
    {
      "lugar_id": 1,
      "zona_id": 1,
      "fila": "A",
      "numero": "2",
      "estado": "DISPONIBLE"
    }
  ]
}
```

### RUTAS DE ENTRADA 

#### **`GET` GET ALL**
Devuelve todos los registros de Entrada.

Params:
- ``entity``: entrada

```bash
http://localhost/Microservicios/src/index.php?entity=entrada
```

#### **`GET` GET ONE BY ID**
Devuelve un registro Entrada que coicida con el ID proporcionado.

Params:
- ``entity``: entrada
- ``id``: ENT-2025-00001

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&id=ENT-2025-00001
```

#### **`POST` CREATE**
Crea un registro Entrada.

Params:
- ``entity``: entrada

```bash
http://localhost/Microservicios/src/index.php?entity=entrada
```

Body (raw JSON):

```json
{
    "pago_id": 1,
    "sesion_id": 1,
    "zona_id": 1,
    "precio_id": 1,
    "asiento_id": 1
}
```

#### **`PUT` UPDATE**
Actualiza un registro Entrada especificado por su ID.

Params:
- ``entity``: entrada
- ``id``: ENT-2025-00021

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&id=ENT-2025-00021
```

Body (raw JSON):

```json
{
    "estado": "USADA"
}
```

#### **`DELETE` DELETE**
Elimina un registro Entrada especificado por su ID.

Params:
- ``entity``: entrada
- ``id``: ENT-2025-00021

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&id=ENT-2025-00021
```

#### **`GET` GET BY SESION**
Devuelve un registro Entrada que coicida con la Sesion proporcionada.

Params:
- ``entity``: entrada
- ``action``: getEntradasBySesion
- ``sesionId``: 3

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=getEntradasBySesion&sesionId=3
```

#### **`GET` GET BY PAGO**
Devuelve un registro Entrada que coicida con el Pago proporcionado.

Params:
- ``entity``: entrada
- ``action``: getEntradasByPago
- ``pagoId``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=getEntradasByPago&pagoId=1
```

#### **`GET` GET BY ZONA AND SESION**
Devuelve un registro Entrada que coicida con Zona y SESION proporcionadas.

Params:
- ``entity``: entrada
- ``action``: getEntradasByZonaAndSesion
- ``zonaId``: 4
- ``sesionId``: 1

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=getEntradasByZonaAndSesion&zonaId=4&sesionId=1
```

#### **`GET` GET BY ESTADO**
Devuelve un registro Entrada que coicida con el estado proporcionado.

Params:
- ``entity``: entrada
- ``action``: getEntradasByEstado
- ``estado``: VALIDA (solo VALIDA, USADA, CANCELADA, REEMBOLSADA)

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=getEntradasByEstado&estado=VALIDA
```

#### **`GET` GET BY CÓDIGO**
Devuelve un registro Entrada que coicida con el código proporcionado. Este método es inútil, pues hace lo mismo que el Get One by ID.

Params:
- ``entity``: entrada
- ``action``: getEntradaByCodigo
- ``codigo``: ENT-2025-00001

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=getEntradaByCodigo&codigo=ENT-2025-00001
```

#### **`POST` CAMBIAR ESTADO**
Cambia el estado de la Entrada.

Params:
- ``entity``: entrada
- ``action``: cambiarEstado
- ``codigo``: ENT-2025-00001
- ``estado``: VALIDA (solo VALIDA, USADA, CANCELADA, REEMBOLSADA)

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=cambiarEstado&codigo=ENT-2025-00001&estado=VALIDA
```

#### **`POST` VALIDAR ENTRADA**
Valida la Entrada.

Params:
- ``entity``: entrada
- ``action``: validarEntrada
- ``codigo``: ENT-2025-00001

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=validarEntrada&codigo=ENT-2025-00001
```

#### **`POST` CANCELAR ENTRADA**
Cancela la Entrada.

Params:
- ``entity``: entrada
- ``action``: cancelarEntrada
- ``codigo``: ENT-2025-00001

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=cancelarEntrada&codigo=ENT-2025-00001
```

#### **`POST` CREATE ENTRADAS**
Crea entradas de forma masiva.

Params:
- ``entity``: entrada
- ``action``: crearMultiplesEntradas

```bash
http://localhost/Microservicios/src/index.php?entity=entrada&action=crearMultiplesEntradas
```

Body (raw JSON):

```json
{
  "entradas": [
    {
      "pago_id": 1,
      "sesion_id": 1,
      "zona_id": 1,
      "precio_id": 1,
      "asiento_id": 1
    },
    {
      "pago_id": 1,
      "sesion_id": 1,
      "zona_id": 2,
      "precio_id": 2,
      "asiento_id": null,
      "fila": "B",
      "asiento_numero": 10
    },
    {
      "pago_id": 2,
      "sesion_id": 3,
      "zona_id": 1,
      "precio_id": 3
    }
  ]
}
```