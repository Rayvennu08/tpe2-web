## tpe2-web.
[**→Clickee aqui para acceder a este proyecto.←**](https://github.com/Rayvennu08/tpe2-web)



## **Development Server.**
Para correr este servidor de motor MVC se necesitan intalar dos programas:
*Xampp* para poder correr la base de datos (MySQL) y levantar el servidor (Apache), y el programa *Postman* (**https://www.postman.com/downloads/**), 
para poder probar e interactuar con la API de este trabajo.

---

Una vez instalado *Xampp* debe iniciar el servidor Apache para poder concretar el paso posterior a este, e iniciar a su vez MySQL desde *Xampp*, 
luego de ello deberá ir a la carpeta donde haya instalado *Xampp* (Por ejemplo: C:\xampp\htdocs\tpe2-web)
en dicha carpeta copiará el repositorio para posteriormente poder levantar el servidor.

---

Luego, deberá abrir su navegador de preferencia y escribir en la barra de búsqueda:
**http://localhost/phpmyadmin**

---

En dicha url creará una nueva base de datos llamada *'listajuegos'*, una vez creada deberá importar el archivo ***listajuegos.sql*** que se encuentra
en el repositorio; de esta manera obtendrá los datos necesarios para disponer del contenido del proyecto e interactuar con éste mediante *Postman*.

---

Una vez realizados los pasos anteriores deberá ejecutar el programa *Postman* y seguir las siguientes instrucciones.


## **Función del Router.**
A continuación se explicará que hace cada ruta, para que le sea mas fácil de usar en *Postman* y saber qué rutas escribir, y así, consecuentemente,
poder interactuar con el API.


## **Verbo GET.**

1. `$router->addRoute('games', 'GET', 'GameApiController', 'getGames');` Ejemplo: `http://localhost/tpe2-web/api/games` o `http://localhost/tpe2-web/api/games?sort=sinopsis`
Trae todos los juegos existentes sin filtro alguno, o mediante el uso de *sort* puede diferenciarlos con los parametros utilizables en sort: { "id_juego", "juego_name", "sinopsis", "calificacion", "id_brand"}.

2. Ejemplo: `http://localhost/tpe2-web/api/games?order=asc` Ordena los juegos en forma descendente o ascendente (ASC o asc, DESC o desc).

3. Ejemplo: `http://localhost/tpe2-web/api/games?sort=juego_name&order=asc` Ordena los juegos, dependiendo del parametro que se envie, 
en forma descendente o ascendente (ASC o asc / DESC o desc) segun del mas nuevo al mas viejo, y viceversa.

4. (Ejemplo sobre filtrado, punto en desarrollo)


## **Verbo GET** (*Busqueda por ID*).

1. `$router->addRoute('game/:ID', 'GET', 'GameApiController', 'getGame');` Ejemplo: `http://localhost/tpe2-web/api/game/1` 
Busca un juego por su ID.



## **Verbo DELETE** (*Eliminación por ID*).

1. `$router->addRoute('game/:ID', 'DELETE', 'GameApiController', 'deleteGame');` Ejemplo: `http://localhost/tpe2-web/api/game/5` Elimina un juego por su ID.



## **Verbo POST** (*Agregado de nuevo item*).

1. `$router->addRoute('game', 'POST', 'GameApiController', 'insertGame');` Ejemplo: `http://localhost/tpe2-web/api/game/`

Para agregar el juego que desee, debe dirigirse al apartado de *Body* en la seccion debajo de la barra de busqueda por url de Postman, para luego agregar
algo como el siguiente ejemplo:

~~~
{
    "juego_name": "Bla bla",
    "sinopsis": "uno dos tres cuatro cinco seis",
    "calificacion": 9,
    "id_brand": "Electronic Arts"
}
~~~



