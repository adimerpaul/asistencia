<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
        $user = User::create([
            'name' => 'Romina Astete',
            'username' => 'admin',
//            'avatar' => 'default.png',
//            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123Admin'),
            'role' => 'Administrador',
        ]);

//        1ER AÑO DE FORMACIÓN:	3ER AÑO DE FORMACIÓN:
//Lengua Extranjera Inglés:	Técnico Tecnológico:
//• Básico	• Contabilidad
//• Intermedio	• Gastronomía
//• Avanzado	• Ofimática
//	• Plataformas virtuales
//	• Herramientas digitales
//	• Dibujo técnico
//	• Diseño gráfico
//	• Instalaciones eléctricas
//	• Plomería
//	• Reparación de instrumentos musicales
//	• Soldadura
//	• Cerrajería
//	• Otros a requerimiento
//2DO AÑO DE FORMACIÓN:	4TO AÑO DE FORMACIÓN:
//Artístico - Cultural:	Temáticas Emergentes en la Especialidad:
//• Danzas nacionales	• Parvulario
//• Danzas internacionales	• Cosmovisiones y Espiritualidades
//• Interpretación de instrumentos musicales	• Lenguaje
//• Teatro	• Oratoria y Declamación
//• Canto, poesía	• Lectura comprensiva
//• Dibujo artístico	• Técnicas de estudio
//• Pintura	• Redacción académica y administrativa
//• Música	• Razonamiento lógico matemático
//• Literatura	• Taller
//• Escultura	• Otros a requerimiento
//• Artesanía
//• Textiles y confecciones
//• Alfarería
//• Otros a requerimiento
//        class Curso extends Model{
//            use SoftDeletes;
//            protected $fillable = [
//                'nombre',
//                'descripcion',
//                'formacion',
//                'tipo',
//                'icono',
//            ];
//            protected $hidden = [
//                'deleted_at',
//                'created_at',
//                'updated_at',
//            ];
//        }
//        iconos:[
//        { label: 'Quimica', icon: 'fa-solid fa-flask-vial' },
//        { label: 'Matemáticas', icon: 'fa-solid fa-book-open-reader' },
//        { label: 'Física', icon: 'fa-solid fa-chalkboard-user' },
//        { label: 'Biología', icon: 'fa-solid fa-graduation-cap' },
//        { label: 'Programación', icon: 'fa-solid fa-laptop-code' },
//        { label: 'Robótica', icon: 'fa-solid fa-robot' },
//        { label: 'Redes', icon: 'fa-solid fa-network-wired' },
//        { label: 'Base de Datos', icon: 'fa-solid fa-database' },
//        { label: 'Lenguajes', icon: 'fa-solid fa-language' },
//        { label: 'Historia', icon: 'fa-solid fa-landmark' },
//        { label: 'Geografía', icon: 'fa-solid fa-globe' },
//        { label: 'Arte', icon: 'fa-solid fa-paintbrush' },
//        { label: 'Música', icon: 'fa-solid fa-music' },
//        { label: 'Educación Física', icon: 'fa-solid fa-running' },
//        { label: 'Literatura', icon: 'fa-solid fa-book' },
//        { label: 'Ciencias Sociales', icon: 'fa-solid fa-users' },
//        { label: 'Ciencias Naturales', icon: 'fa-solid fa-leaf' },
//        { label: 'Computación', icon: 'fa-solid fa-desktop' },
//        { label: 'Aymara', icon: 'fa-solid fa-comments' },
//        { label: 'Lenguaje', icon: 'fa-solid fa-comment-dots' },
//        { label: 'Quechua', icon: 'fa-solid fa-comments' },
//        { label: 'Música', icon: 'fa-solid fa-music' },
//        { label: 'Educación Física', icon: 'fa-solid fa-running' },
//        { label: 'Otros', icon: 'fa-solid fa-ellipsis' }
//      ]
        $cursos = Curso::insert([
            [
                'nombre' => 'Lengua Extrangera Ingles basico',
                'descripcion' => 'Lengua Extrangera Ingles',
                'formacion' => '1er Año de Formación',
                'tipo' => 'Lenguajes',
                'icono' => 'fa-solid fa-language',
            ],
            [
                'nombre' => 'Lengua Extrangera Ingles intermedio',
                'descripcion' => 'Lengua Extrangera Ingles',
                'formacion' => '1er Año de Formación',
                'tipo' => 'Lenguajes',
                'icono' => 'fa-solid fa-language',
            ],
            [
                'nombre' => 'Lengua Extrangera Ingles avanzado',
                'descripcion' => 'Lengua Extrangera Ingles',
                'formacion' => '1er Año de Formación',
                'tipo' => 'Lenguajes',
                'icono' => 'fa-solid fa-language',
            ],
            [
                'nombre' => 'Danzas Nacionales',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Música',
                'icono' => 'fa-solid fa-music',
            ],
            [
                'nombre' => 'Danzas Internacionales',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Música',
                'icono' => 'fa-solid fa-music',
            ],
            [
                'nombre' => 'Interpretación de Instrumentos Musicales',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Música',
                'icono' => 'fa-solid fa-music',
            ],
            [
                'nombre' => 'Teatro',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Canto, Poesía',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Dibujo Artístico',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Pintura',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Música',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Música',
                'icono' => 'fa-solid fa-music',
            ],
            [
                'nombre' => 'Literatura',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Literatura',
                'icono' => 'fa-solid fa-book',
            ],
            [
                'nombre' => 'Escultura',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            // 2DO AÑO DE FORMACIÓN (continuación)
            [
                'nombre' => 'Artesanía',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Textiles y confecciones',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Alfarería',
                'descripcion' => 'Artístico - Cultural',
                'formacion' => '2do Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
//            [
//                'nombre' => 'Otros a requerimiento',
//                'descripcion' => 'Artístico - Cultural',
//                'formacion' => '2do Año de Formación',
//                'tipo' => 'Otros',
//                'icono' => 'fa-solid fa-ellipsis',
//            ],

            // 3ER AÑO DE FORMACIÓN
            [
                'nombre' => 'Contabilidad',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Contabilidad',
                'icono' => 'fa-solid fa-book-open-reader',
            ],
            [
                'nombre' => 'Gastronomía',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
            [
                'nombre' => 'Ofimática',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Computación',
                'icono' => 'fa-solid fa-desktop',
            ],
            [
                'nombre' => 'Plataformas virtuales',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Computación',
                'icono' => 'fa-solid fa-laptop-code',
            ],
            [
                'nombre' => 'Herramientas digitales',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Computación',
                'icono' => 'fa-solid fa-laptop-code',
            ],
            [
                'nombre' => 'Dibujo técnico',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Diseño gráfico',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Arte',
                'icono' => 'fa-solid fa-paintbrush',
            ],
            [
                'nombre' => 'Instalaciones eléctricas',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Física',
                'icono' => 'fa-solid fa-chalkboard-user',
            ],
            [
                'nombre' => 'Plomería',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
            [
                'nombre' => 'Reparación de instrumentos musicales',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Música',
                'icono' => 'fa-solid fa-music',
            ],
            [
                'nombre' => 'Soldadura',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
            [
                'nombre' => 'Cerrajería',
                'descripcion' => 'Técnico Tecnológico',
                'formacion' => '3er Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
//            [
//                'nombre' => 'Otros a requerimiento',
//                'descripcion' => 'Técnico Tecnológico',
//                'formacion' => '3er Año de Formación',
//                'tipo' => 'Otros',
//                'icono' => 'fa-solid fa-ellipsis',
//            ],

            // 4TO AÑO DE FORMACIÓN
            [
                'nombre' => 'Parvulario',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
            [
                'nombre' => 'Cosmovisiones y Espiritualidades',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
            [
                'nombre' => 'Lenguaje',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Lenguaje',
                'icono' => 'fa-solid fa-comment-dots',
            ],
            [
                'nombre' => 'Oratoria y Declamación',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Lenguaje',
                'icono' => 'fa-solid fa-comment-dots',
            ],
            [
                'nombre' => 'Lectura comprensiva',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Lenguaje',
                'icono' => 'fa-solid fa-comment-dots',
            ],
            [
                'nombre' => 'Técnicas de estudio',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
            [
                'nombre' => 'Redacción académica y administrativa',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Lenguaje',
                'icono' => 'fa-solid fa-comment-dots',
            ],
            [
                'nombre' => 'Razonamiento lógico matemático',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Matemáticas',
                'icono' => 'fa-solid fa-book-open-reader',
            ],
            [
                'nombre' => 'Taller',
                'descripcion' => 'Temáticas Emergentes en la Especialidad',
                'formacion' => '4to Año de Formación',
                'tipo' => 'Otros',
                'icono' => 'fa-solid fa-ellipsis',
            ],
//            [
//                'nombre' => 'Otros a requerimiento',
//                'descripcion' => 'Temáticas Emergentes en la Especialidad',
//                'formacion' => '4to Año de Formación',
//                'tipo' => 'Otros',
//                'icono' => 'fa-solid fa-ellipsis',
//            ],
        ]);
    }
}
