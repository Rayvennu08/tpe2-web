## Trabajo práctico especial de Web 2
[**→Clickee aquí para acceder a este proyecto.←**](https://github.com/Rayvennu08/tpe2-web)

---

### **Development Server** :spiral_notepad:	
Para correr este servidor de motor MVC se necesitan intalar dos programas:

*Xampp* (**https://www.apachefriends.org/es/download.html**) para poder correr la base de datos (*MySQL*) y levantar el servidor (*Apache*). 

Junto al programa *Postman* (**https://www.postman.com/downloads/**) 
para poder probar e interactuar con la API de este trabajo.


Una vez instalado *Xampp* debe iniciar el servidor Apache para poder concretar el paso posterior a este, e iniciar a su vez MySQL desde *Xampp*, 
luego de ello deberá ir a la carpeta donde haya instalado *Xampp* (Por ejemplo: C:\xampp\htdocs\tpe2-web)
en dicha carpeta copiará el repositorio para posteriormente poder levantar el servidor.


Luego, deberá abrir su navegador de preferencia y escribir lo siguiente en su barra de búsqueda:
**http://localhost/phpmyadmin**


En dicha url creará una nueva base de datos llamada *'listajuegos'*, una vez creada deberá importar el archivo ***listajuegos.sql*** que se encuentra
en el repositorio, o copiar y pegar toda el codigo SQL que se encuentra en la carpeta *database* dentro del proyecto; de esta manera obtendrá los datos necesarios para disponer del contenido del proyecto e interactuar con éste mediante *Postman*.


Una vez realizados los pasos anteriores deberá ejecutar el programa *Postman* y seguir las siguientes instrucciones.

---

# Recurso 'games' :books:	

***A partir de esta sección del informe se detallarán y ejemplificarán los verbos utilizados como funciones didácticas para quien trabaje con este proyecto mediante Postman.***


## **Función del Router** :page_facing_up:	
A continuación se explicará que hace cada ruta respecto al recurso utilizado en el trabajo (recurso 'games'), para que le sea mas fácil de usar en *Postman* y saber qué rutas escribir, y así, consecuentemente, poder interactuar con la API.


## **Funcionalidades GET en recurso 'games'** :orange_book:	

1. Ejemplo: `http://localhost/tpe2-web/api/games` 

Trae todos los juegos existentes sin filtro alguno

1.2 Ejemplo *sort*: `http://localhost/tpe2-web/api/games?sort=sinopsis`

 ***A CONTINUACION SE DEJA UN CUADRO PARA DIFERENCIAR, Y RECONOCER, LOS PARAMETROS DE CATEGORIA brands DIVIDIDOS POR id_brand, CADA NUMERO (id) HACE REFERENCIA A UN NOMBRE DE EMPRESA:***

```
         id_brand         brand_name
            1          Activision Blizzard 
            2          Electronic Arts
            3          From Software
            4          Riot games
            5          Ubisoft
            6          Valve    
```

O mediante el uso de *sort* puede diferenciarlos con los parametros utilizables:
{"id_juego", "juego_name", "sinopsis", "calificacion", "id_brand"}.

2. Ejemplo ***order***: `http://localhost/tpe2-web/api/games?order=asc` 

Ordena los juegos en forma descendente o ascendente (ASC o asc, DESC o desc).

3. Ejemplo ***sort*** y ***order***: `http://localhost/tpe2-web/api/games?sort=juego_name&order=asc`

Ordena los juegos, dependiendo del parametro que se envie ({"id_juego", "juego_name", "sinopsis", "calificacion", "id_brand"}) y 
en forma descendente o ascendente (ASC o asc / DESC o desc) segun del elemento más nuevo al mas viejo, y viceversa.

4. Ejemplo ***filtro***: `http://localhost/tpe2-web/api/games?filtro=blo` para filtrar juegos que contengan *blo* en sus caracteristicas en ese caso.

5. Ejemplo de unión de los 3 parametros ***sort***, ***order*** y ***filtro***: `http://localhost/tpe2-web/api/games?sort=juego_name&filtro=call&order=asc` Esto traerá los juegos que en su *nombre* (juego_name) contengan la palabra "call" (filtro) en forma ascendente (order).

6. Ejemplo ***paginación***: `http://localhost/tpe2-web/api/games?start=16&products=5` el parametro *start* representa la pagina que se busque mostrar y el parametro *products* representa la cantidad de juegos, en este caso, que se deseen mostrar.

7. Ejemplo: `http://localhost/tpe2-web/api/game/1` 

Busca un juego por su ID.



## **Funcionalidades DELETE en recurso 'games'** (*Eliminación por ID de un item*) :blue_book:	

- Ejemplo: `http://localhost/tpe2-web/api/game/5` 

Elimina un juego por su ID.



## **Funcionalidades POST en recurso 'games'** (*Agregado de nuevo item*) :closed_book:	

- Ejemplo: `http://localhost/tpe2-web/api/game/`

Para agregar el juego que desee, debe dirigirse al apartado de *Body* en la seccion debajo de la barra de busqueda por url de Postman y elegir la opción '*raw*', y luego agregar algo como el siguiente ejemplo:

```
{
    "juego_name": "Bla bla",
    "sinopsis": "uno dos tres cuatro cinco seis",
    "calificacion": 9,
    "id_brand": "Electronic Arts"
    (Debe ser una empresa existente en la base de datos, de no ser así el programa notificará un error).
}
```
