<?php

namespace App;

class NewsRepository
{
    public function getAllNews()
    {
        $rawNews = [
            [
                'id'         => 1,
                'titulo'     => 'El Futuro es Hoy: La IA Generativa',
                'contenido'    => 'Un análisis profundo sobre cómo la inteligencia artificial está cambiando el mundo del arte y la programación. El potencial es ilimitado, pero también presenta nuevos desafíos éticos y creativos.',
                'fecha' => '2025-10-09',
                'imagen' => 'https://elprisma.com.py/wp-content/uploads/2025/01/IA-Microsof-1536x864.jpg',
            ],

            [
                'id'         => 2,
                'titulo'     => 'Descubren Nuevo Planeta Habitable',
                'contenido'    => 'Astrónomos confirman la existencia de un exoplaneta con condiciones similares a la Tierra a solo 20 años luz. La comunidad científica está emocionada por las posibilidades de investigar su atmósfera.',
                'fecha' => '2025-10-08',
                'imagen' => 'https://concepto.de/wp-content/uploads/2019/10/planeta-tierra-e1570462065623.jpg',
            ],

            [
                'id'         => 3,
                'titulo'     => 'La Receta Secreta para un Café Perfecto',
                'contenido'    => 'Baristas de todo el mundo comparten sus secretos para preparar la taza de café ideal en casa. Desde la molienda del grano hasta la temperatura del agua, cada detalle cuenta.',
                'fecha' => '2025-10-07',
                'imagen' => 'https://www.nutrimarket.com/blog/wp-content/uploads/2020/05/cafe-por-la-manana.jpg',
            ],

            [
                'id'         => 4,
                'titulo'     => 'El Boom de los Teclados Mecánicos',
                'contenido'    => '¿Por qué todos los programadores y gamers están cambiando a teclados mecánicos? Exploramos los beneficios de los diferentes tipos de \'switches\' y la cultura de la personalización.',
                'fecha' => '2025-10-06',
                'imagen' => 'https://d1q3zw97enxzq2.cloudfront.net/images/K65_PLUS_WIRELESS_14_2.width-1000.format-webp.webp',
            ],
        ];

        $newsObjects = [];
        foreach ($rawNews as $item) {
            $newsObjects[] = new News(
                $item['id'],
                $item['titulo'],
                $item['contenido'],
                $item['fecha'],
                $item['imagen']
            );
        }
        return $newsObjects;
    }

    public function getNewsById($id)
    {
        foreach ($this->getAllNews() as $news) {
            if ($news->id == $id) {
                return $news;
            }
        }
        return null;
    }
}
