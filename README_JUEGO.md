# InventCraft

![Ilustración del juego](public/images/home/principal.png)

## Descripción del Proyecto

Se trata de un juego de estrategia individual hecho en Laravel, donde los jugadores compiten por alcanzar la mayor puntuación y construir la Estación Espacial, representando una carrera por el desarrollo tecnológico y la exploración.


## Mapa

![Mapa](public/images/home/mapa.jpg)
- El mapa principal será un cuadrado de 3X3. Esto significa que habrá un total de 9 zonas.
- Cada zona contiene recursos únicos que no están disponibles en otras zonas.
- En las zonas es posible encontrar inventos.
- La dificultad de recolectar recursos depende de la zona, ya que en cada una pueden ocurrir diferentes eventos que le hagan perder los recursos recolectados total o parcialmente. Cuando ocurre un evento, el jugador es informado con el nombre del evento, su descripción y el porcentaje de pérdida de recursos.
- El jugador puede crear inventos en todas las zonas.


## Progresión de Jugadores

![Progresion](public/images/home/progresion.png)
- Comienza con el nivel 1 y no se ha establecido nivel máximo que el jugador pueda alcanzar
- Experiencia (EXP) ganada por:
  1. Desplazamientos entre zonas exitoso
  2. Creación de inventos
  3. Exploración de una zona y la recolección de recursos en ella
  4. Construcción y mejora de edificios
- Cualidades del jugador:
  1. Los jugadores obtendrán 15 puntos por nivel y 15 puntos iniciales por registrarse. Estos puntos se podrán repartir como deseen.
  2. Una vez repartidos no podrán volverse a usar.
    1. Suerte: Incrementa la probabilidad de encontrar recursos durante la exploración.
    2. Vitalidad: Reduce el tiempo de construcción de edificios.
    3. Ingenio: Incrementa la eficiencia al inventar y reduce el tiempo de creación de inventos.
    4. Velocidad: Reduce el tiempo de movimiento entre zonas.


## Acciones posibles

![Acciones](public/images/home/acciones.png)
- Recolectar: Recoger los recursos de una zona.
  - Los jugadores exploran zonas para recolectar materiales y/o inventos.
- Moverse: Cambiar de una zona a otra.
- Inventar: Crear inventos a partir de un invento anterior o de materiales encontrados.
    - Los jugadores pueden crear inventos a partir de materiales recolectados.
    - Los inventos sirven para crear edificios.
- Construir: Crear edificios.
  - Los edificios otorgan bonificaciones permanentes que afectan a las cualidades del jugador.
  - Construir la estación espacial será la clave para la victoria. Para poder construirla, todos los demás edificios deben estar construidos (nivel > 0) y tener eficiencia del 100%.


## Tiempo y Progresión

1. Duración de las Acciones: Los jugadores eligen cuánto tiempo dedicar a una acción con un mínimo de 30 minutos y hasta un máximo de 600 minutos.

  - Explorar: Cuanto más tiempo dediquen, mayor será la cantidad de objetos encontrados.
    - La fórmula para el cálculo de la probabilidad de encontrar cada uno de los materiales disponibles en la zona es:
      > min((50 - eficiencia_material + suerte_jugador + (tiempo / 30)), 100) >= valor aleatorio entre 0 y 70
      > La probabilidad se limita a un máximo de 100
    - De cada objeto se pueden conseguir un valor aleatorio de cantidades según la eficiencia.
      - Valor mínimo 1
      - Valor máximo 9 para aquellos materiales con una eficiencia inferior o igual a 22%
      - Valor máximo 6 para aquellos materiales con una eficiencia superior a 22% e inferior o igual a 30%
      - Valor máximo 3 para aquellos materiales con una eficiencia superior a 30%
    - Por ejemplo, un jugador con 30 de suerte, pudiendo encontrar un material con 25% de eficiencia e invirtiendo 30 minutos: 
      - Su probabilidad de encontrar ese material es = min((50 - 25 + 30 + (30/30)), 100) = min(56, 100) = 56
      - Por lo que siempre que salga un número aleatorio entre 0 y 56 (si es >= al random entre 0 y 70) se realizará un random para averiguar la cantidad (entre 1 y 6 por tener una eficiencia del 25%) que obtendrá de ese material.

  - Moverse: El tiempo de movimiento entre zonas depende de la distancia y la velocidad del jugador.
    - La fórmula varía según la distancia:
      - Distancia 0 (misma zona): 0 minutos
      - Distancia 1: 50 - velocidad_jugador minutos
      - Distancia 2: 50 + (50 / velocidad_jugador) minutos
      - Distancia 3 o más: (2 * 50) + (50 / velocidad_jugador) minutos
    - La velocidad del jugador reduce el tiempo de movimiento.
    - Por ejemplo, un jugador con 20 de velocidad moviéndose a una zona a distancia 1:
      > Tiempo = 50 - 20 = 30 minutos

  - Inventar: Dedicando más tiempo, aumentará la eficiencia del invento hasta 10 horas como máximo.
    - La fórmula de eficiencia será:
      > Eficiencia del material + (ingenio del jugador / 10) + (tiempo invertido / 30)
    - El tiempo de creación se reduce por el valor de Ingenio:
      > Tiempo de acción = tiempo elegido - ingenio del jugador (mínimo 1 minuto)
    - Por ejemplo, un jugador de ingenio 20 invirtiendo 60 minutos puede crear una piedra afilada con un material X:
      - Suponemos que la eficiencia del material es 40,5%
      - La eficiencia total será 40,5 + (20/10) + (60/30) = 40,5 + 2 + 2 = 44,5%
      - El tiempo real de la acción será 60 - 20 = 40 minutos

  - Construir Edificios: Los edificios tienen tiempos de construcción dependiendo de su nivel.
    - La fórmula será:
      > (600 / (nivel_usuario + 1)) * nivel_edificio - vitalidad_usuario
      > Donde 600 son minutos (10 horas)
    - La vitalidad del jugador reduce el tiempo de construcción.
    - Por ejemplo, un jugador de nivel 3 con 15 de vitalidad construyendo un edificio de nivel 2:
      > Tiempo base = (600 / (3+1)) * 2 = 300 minutos
      > Tiempo total = 300 - 15 = 285 minutos


## Construcciones y niveles de edificios

![Edificios](public/images/home/edificios.png)
Tipos de Edificios e inventos requeridos:
- Estación de Transporte: Carro, Rueda, Barco
- Taller de Manufactura:	Herramientas de piedra, Cuerdas, Lanza, Arco y flechas, Hacha, Cestas, Torno
- Granja: Agricultura, Ganadería, Arado, Trampas para caza, Sistema de riego automatizado
- Planta de Energía:	Fuego, Canales
- Fundición de Metales:	Metalurgia, Vidrio
- Taller de Cerámica:	Cerámica, Alfarería, Horno de alta temperatura
- Fábrica de Textiles:	Tela
- Sistema de Acueductos:	Acueducto, Molino de agua
- Estación espacial:	Requiere todos los edificios con eficiencia 100 %

Al construir un edificio obtiene la eficiencia media de los inventos con los que se ha creado siguiendo la formula siguiente:
> Eficiencia = (suma de eficiencia de inventos / número inventos) / max(2, 1 + nivel)

Ejemplo - Edificio de nivel 1:
> Estación de Transporte:	(1 Carro 50%, 1 Rueda 100%, 1 Barco 25.6%) / 3 = 58.53% / max(2, 1+1) = 58.53% / 2 = 29,26%

Cada nivel de edificio requiere el doble de los inventos.

Ejemplo - Edificio de nivel 2:
- Eficiencia anterior = 29,26%
> Estación de Transporte: 1 Carro 50%, 1 Carro 40%, 1 Rueda 80%,1 Rueda 100%, 1 Barco 25.6%, 1 Barco 22.4% = 53% / max(2, 1+2) = 53% / 3 = 17.67% + 29,26% = 46.93%

Ejemplo - Edificio de nivel 10:
> Si la eficiencia media de los inventos es 50%: 50% / max(2, 1+10) = 50% / 11 = 4.55% (más viable que con la fórmula anterior que sería 50% / 20 = 2.5%)

**No hay límite máximo de nivel.** El nivel podrá subirse hasta conseguir una eficiencia de 100% en el edificio. La única condición para construir la Estación Espacial es que todos los demás edificios tengan eficiencia 100%, independientemente del nivel alcanzado.

Cada edificio aumentará las estadísticas del jugador:
- Estación de Transporte: Velocidad y Suerte en 1 punto
- Taller de Manufactura: Velocidad e Ingenio en 1 punto
- Granja: Vitalidad e Ingenio en 1 punto
- Planta de Energía: Ingenio y Velocidad en 1 punto
- Fundición de Metales: Vitalidad y Suerte en 1 punto
- Taller de Cerámica: Ingenio y Suerte en 1 punto
- Fábrica de Textiles: Suerte y Velocidad en 1 punto
- Sistema de Acueductos: Suerte y Vitalidad en 1 punto
- Estación espacial: Aumentará una estadística calculada de forma aleatoria en 5 puntos. Solo se puede construir cuando todos los demás edificios tienen eficiencia del 100%.


## Recursos

- El jugador tendrá un inventario propio que podrá almacenar recursos ilimitados.
- Los inventos creados consumen recursos como materia prima y/o otros inventos para su creación.
- Los inventos han de ser de nivel inferior al del jugado, lo que ocurrirá siempre ya que todos los tipos de inventos tienen un nivel requerido de 1 que es con el nivel que comienza el jugador.
- La probabilidad de encontrar inventos se obtiene con la siguiente fórmula: 50 + suerte del jugador + (tiempo dedicado a la exploración de la zona / 30).
  - Si su probabilidad es igual o superior a 85 y sin que ocurran eventos en la zona, encontrará 3 inventos, el tipo de los mismos se determinará de forma aleatoria.
  - Si su probabilidad es igual o superior a 60 y sin que ocurran eventos en la zona, encontrará 2 inventos, el tipo de los mismos se determinará de forma aleatoria.
  - Si su probabilidad es igual o superior a 40 y sin que ocurran eventos en la zona, encontrará 1 inventos, el tipo del mismo se determinará de forma aleatoria.


## Condiciones de Victoria

- Objetivo Principal: Construir la Estación espacial garantiza la victoria total.
- Requisitos para construir la Estación Espacial:
  - Todos los demás edificios deben estar construidos (nivel > 0)
  - Todos los demás edificios deben tener eficiencia del 100%
  - No hay límite máximo de nivel para los edificios, solo se requiere eficiencia 100%
- Al construir la Estación Espacial, se otorga una bonificación aleatoria de 5 puntos a una estadística del jugador. 

