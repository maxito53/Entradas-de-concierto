Igual que en la práctica 2, hice otro proyecto para la 3.

No pude dedicarle el tiempo que hubiese necesitado para entregar la práctica completa, hay muchas cosas hechas a prisa y cosas que ya no me da tiempo a corregir.

En lo que respeta al enunciado añadí todo lo que pide menos alguna Interface. 

Me enfoque durante la práctica en intentar hacer bastante programación en si, ya que la UF que yo necesito recuperar es la 1, y capaz deje vastante de lado otras cosas.

Queria hacer que las rutas a la API se pillaran con rutas relativas, para poder abirir los proyectos en cualquier carpeta, mientras estuviesen juntos, pero no me dio tiempo, **las rutas se cogen si los proyectos están en la raíz de la carpeta de Apache**.

Puede ser que pida permisos la carpeta de imagenes para poder crear en ella con la API.

El FrontOfice en resumen te muestra en Home todas las sesiones de conciertos que hay y te deja escoger para comprar, ahí te lleva a un formulario para meter los datos necesarios para crear la entrada y al finalizar se creará y te da opción de abrir el PDF de la entrada.

Si se crea la base de datos de 0:

- Ejecutar ```sql CREATE DATABASE TuraMax_Entradas```.
- Ejecutar ```bash php bin/doctrine.php orm:schema-tool:create``` en Entradas o Microservicios, en la raíz de cualquer de los 2 proyectos.
- Para insertar los datos por defecto antes de nada, ejecutar el index.php de Entradas, por ejemplo, ejecutar: ```http://localhost/Entradas/src/index.php?data=2025-05-10```
- Ahora ya está todo preparado para que todo funcione bien, en el Front Office para acceder hay que poner los datos del administrador, que el que está por defecto en base de datos es ``admin@admin.com`` y de contraseña ``Password123!!``.