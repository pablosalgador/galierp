# GaliERP

Software de gestión empresarial (ERP) para autónomos y pequeñas empresas basado en Symfony 4.4

## Requisitos
* Composer
* MySQL Server

## Instalación

### 1.- Preparación del entorno

a) Instalación de Composer

`sudo apt-get install composer`

b) Instalación MySQL Server

`sudo apt-get install mysql-server`

c) Instalación PHP 7.2+ y módulos necesarios

`sudo apt-get install php7.2 php-xml php-mbstring php-zip php-mysql`


## 2.- Despliegue

`git clone https://github.com/pablosalgador/galierp.git`
`cd galierp`
`composer install`


### 2.- Conexión con Base de Datos

a) Crear un fichero llamado `.env.local` en la raiz de la aplicación con el siguietne contenido:

`DATABASE_URL=mysql://dbuser:dbpass@dbhost:dbport/dbname`

* **dbuser**: Nombre del usuario de la base de datos
* **dbpass**: Contraseña de la base de datos
* **dbhost**: Dirección de la base de datos
* **dbname**: Nombre de la base de datos


b) Crear scheme de base de datos

`php bin/console doctrine:schema:create`


c) Inserción usuario de prueba 

Ejecutar la siguiente consulta en la base de datos:

`insert into usuario values(null,'superadmin@galierp.gal','["ROLE_SUPER_ADMIN"]','$argon2id$v=19$m=65536,t=4,p=1$nrIOpf9YUxbp8+0971Uh3A$Us7Izm4M5fvYDTrzThM5Z0aRxeAOcQRjASWAsB71j5s','Superadministrador','');`

Esto creará el usuario superadmin@galierp.gal con la contraseña abc123

La contraseña se codifica mediante el algoritmo bcrypt


### 3.- Inicio del servidor embedido de Symfony 4.4


`php bin/console server:run [ipserver][port]`

* **ipserver**: Dirección ip del servidor
* **port**: Número de puerto


