<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeCta;

class HomeCtaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ctas = [
            [
                'title' => 'Ver Chaquetas',
                'icon' => 'fas fa-vest',
                'description' => 'Descubre nuestra colección de chaquetas para niños y niñas',
                'cta_text' => 'Ver Chaquetas',
                'cta_url' => '/catalogo?categoria=chaquetas',
                'order' => 1,
            ],
            [
                'title' => 'Ver Vestidos',
                'icon' => 'fas fa-dress',
                'description' => 'Encuentra el vestido perfecto para tu pequeña',
                'cta_text' => 'Ver Vestidos',
                'cta_url' => '/catalogo?categoria=vestidos',
                'order' => 2,
            ],
            [
                'title' => 'Ver Faldas',
                'icon' => 'fas fa-tshirt',
                'description' => 'Explora nuestra variedad de faldas para niñas',
                'cta_text' => 'Ver Faldas',
                'cta_url' => '/catalogo?categoria=faldas',
                'order' => 3,
            ],
            [
                'title' => 'Producto Estrella',
                'icon' => 'fas fa-star',
                'description' => 'No te pierdas nuestro producto más vendido',
                'cta_text' => 'Ver Producto',
                'cta_url' => '/catalogo?destacado=1',
                'order' => 4,
            ],
            [
                'title' => '¿Necesitas Ayuda?',
                'icon' => 'fas fa-question-circle',
                'description' => 'Estamos aquí para ayudarte con cualquier consulta',
                'cta_text' => 'Ir a Ayuda',
                'cta_url' => '/ayuda',
                'order' => 5,
            ],
        ];

        foreach ($ctas as $cta) {
            HomeCta::create($cta);
        }
    }
}
