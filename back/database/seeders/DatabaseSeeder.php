<?php

namespace Database\Seeders;

use App\Models\Asignacion;
use App\Models\AsignacionEstudiante;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void{
        $user = User::create([
            'name' => 'Romina Astete',
            'username' => 'admin',
//            'avatar' => 'default.png',
//            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123Admin'),
            'role' => 'Administrador',
        ]);
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
        Estudiante::insert([
            ['nombre' => 'APAZA REYNAGA VICTOR HUGO', 'ci' => '7426707'],
            ['nombre' => 'CABRERA AJHUACHO SERGIO ALEJANDRO', 'ci' => '7454341'],
            ['nombre' => 'CACERES HUARACHI NIDIA STEPHANI', 'ci' => '7322548'],
            ['nombre' => 'CHAVEZ MOLINA JOSUE', 'ci' => '7410236'],
            ['nombre' => 'CHOQUE MAMANI MAX CARLOS', 'ci' => '7350489'],
            ['nombre' => 'CHUQUICHAMBI VILLCA LISMER', 'ci' => '7371416'],
            ['nombre' => 'COLQUE FERNANDEZ WILSON', 'ci' => '7367293'],
            ['nombre' => 'COLQUE VIRACA SERGIO RODRIGO', 'ci' => '7362197'],
            ['nombre' => 'CONDORI MAMANI MEDARDO HIBER', 'ci' => '7450306-1G'],
            ['nombre' => 'CORIA CRUZ WILSON FERNANDO', 'ci' => '7988973'],
            ['nombre' => 'CORO CAMATA BRYAN WILBERT', 'ci' => '8544056'],
            ['nombre' => 'GARCIA MIRANDA VICTOR', 'ci' => '7388902'],
            ['nombre' => 'GOMEZ DELGADO JORGE LUIS', 'ci' => '7423652'],
            ['nombre' => 'GUTIERREZ AQUINO MYRNA ISABEL', 'ci' => '7351551'],
            ['nombre' => 'GUTIERREZ CANAZA ELIO OBED', 'ci' => '5755727'],
            ['nombre' => 'HERRERA QUISPE EFRAIN ALEXANDER', 'ci' => '13066778'],
            ['nombre' => 'HUARACHI ARCAYNE JAIME', 'ci' => '7352845'],
            ['nombre' => 'HUARACHI LIA ARIEL', 'ci' => '7319477'],
            ['nombre' => 'HUARACHI MELGAREJO RUBEN CARLOS', 'ci' => '7293844'],
            ['nombre' => 'LIPIRI MEDINA ROGER', 'ci' => '7360131'],
            ['nombre' => 'LIZARAZU FULGUERA JUAN GABRIEL', 'ci' => '7325943'],
            ['nombre' => 'LLAVE TELLERIA CARLOS FREDDY', 'ci' => '12613901'],
            ['nombre' => 'MACHACA CHAVEZ GUILLERMO', 'ci' => '12997463'],
            ['nombre' => 'MAMANI NINA NOE SADRAC', 'ci' => '7324021'],
            ['nombre' => 'MAMANI VILLCA MIGUEL', 'ci' => '7368338'],
            ['nombre' => 'MEYERS FLORES CHRISTIAN RAFAEL', 'ci' => '12772666'],
            ['nombre' => 'PEREZ MARCA ROMER', 'ci' => '7323317'],
            ['nombre' => 'SALAMANCA HUANCA WILSON', 'ci' => '7373616'],
            ['nombre' => 'VASQUEZ FLORES BRANDON LUIS', 'ci' => '7278936'],
        ]);
//        Lic richard willians lopez bazan
//        Lic. gualberto chungara Arancibia
        Docente::insert([
            ['nombre' => 'RICHARD WILLIAMS LOPEZ BAZAN', 'ci' => '12345678'],
            ['nombre' => 'GUALBERTO CHUNGARA ARANCIBIA', 'ci' => '87654321'],
        ]);

        $user = User::where('id', 1)->first();
        $user->docente_id = 1;
        $user->save();

//        1,1,37,2,"""PAMPA HULLAGAS""","TALLER ARTISTICA 1 ""LECTURA MUSICAL 1""",PRIMERA Y SEGUNDA,LIC RICHAR WILIAM LOPEZ BAZAN GUALBERTO CHUNGARA,"PERIMRO, SEGUNDO, y TERCERO",2022,,2025-06-07 06:12:48,2025-06-07 06:15:41
        $asignacion = Asignacion::create([
            'user_id' => $user->id,
            'docente_id' => 1, // RICHARD WILLIAMS LOPEZ BAZAN
            'curso_id' => 1, // Lengua Extrangera Ingles basico
            'unidadEducativa' => 'PAMPA HULLAGAS',
            'taller' => 'TALLER ARTISTICA 1 "LECTURA MUSICAL 1"',
            'fases' => 'PRIMERA Y SEGUNDA',
            'docentesEncargados' => 'LIC RICHAR WILIAM LOPEZ BAZAN GUALBERTO CHUNGARA',
            'anioFormacion' => 'PERIMRO, SEGUNDO, y TERCERO',
            'gestion' => '2022',
        ]);

        $asignacionEstudiante = AsignacionEstudiante::create([
            'asignacion_id' => $asignacion->id,
            'estudiante_id' => 1, // APAZA REYNAGA VICTOR HUGO
        ]);
        $asignacionEstudiante = AsignacionEstudiante::create([
            'asignacion_id' => $asignacion->id,
            'estudiante_id' => 2, // CABRERA AJHUACHO SERGIO ALEJANDRO
        ]);
        $asignacionEstudiante = AsignacionEstudiante::create([
            'asignacion_id' => $asignacion->id,
            'estudiante_id' => 3, // CACERES HUARACHI NIDIA STEPHANI
        ]);
        $asignacionEstudiante = AsignacionEstudiante::create([
            'asignacion_id' => $asignacion->id,
            'estudiante_id' => 4, // CHAVEZ MOLINA JOSUE
        ]);
        $asignacionEstudiante = AsignacionEstudiante::create([
            'asignacion_id' => $asignacion->id,
            'estudiante_id' => 5, // CHOQUE MAMANI MAX CARLOS
        ]);
    }
}
