const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {path: '', component: () => import('pages/IndexPage.vue'), meta: {requiresAuth: true}},
      {path: '/usuarios', component: () => import('pages/usuarios/Usuarios.vue'), meta: {requiresAuth: true}},
      {path: '/cursos', component: () => import('pages/cursos/Cursos.vue'), meta: {requiresAuth: true}},
      {
        path: '/estudiantes',
        component: () => import('pages/estudiantes/Estudiantes.vue'),
        meta: {requiresAuth: true}
      },
      {
        path: '/docentes',
        component: () => import('pages/docentes/Docentes.vue'),
        meta: { requiresAuth: true }
      },
      {
        path: '/asignaciones',
        component: () => import('pages/asignaciones/Asignaciones.vue'),
        meta: { requiresAuth: true }
      },
      {
        path: '/asignaciones/crear',
        component: () => import('pages/asignaciones/AsignacionCreate.vue'),
        meta: { requiresAuth: true }
      },
      {
        path: '/asignaciones/:id/editar',
        component: () => import('pages/asignaciones/AsignacionEdit.vue'),
        meta: { requiresAuth: true }
      },
      {
        path: '/mis-cursos',
        component: () => import('pages/cursos/MisCursosPage.vue'),
        meta: { requiresAuth: true }
      },
      // CursoDetail
      {
        path: '/curso/:id',
        component: () => import('pages/cursos/DetalleCursoPage.vue'),
        meta: { requiresAuth: true }
      },
      // /curso/:id/asistencia → AsistenciaPage.
      {
        path: '/curso/:id/asistencia',
        component: () => import('pages/cursos/AsistenciaPage.vue'),
        meta: { requiresAuth: true }
      },
      // nontas
      {
        path: '/curso/:id/notas',
        component: () => import('pages/cursos/NotasPage.vue'),
      }

]
  },
  {
    path: '/login',
    component: () => import('layouts/Login.vue'),
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
