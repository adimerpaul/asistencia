<template>
  <q-page class="q-pa-md">
    <!-- Filtros izquierda -->
    <q-card>
      <q-card-section>
        <div class="row items-center q-col-gutter-sm">
          <div class="col-12 col-md-2">
            <q-select
              v-model="filterGestion"
              :options="gestiones"
              label="Gestión"
              outlined dense clearable
              @update:model-value="obtenerAsignaciones"
            />
          </div>

          <div class="col-12 col-md-3">
            <q-select
              v-model="filterDocenteId"
              :options="docentes"
              option-label="nombre"
              option-value="id"
              label="Docente"
              outlined dense clearable emit-value map-options
              @update:model-value="obtenerAsignaciones"
            />
          </div>

          <div class="col-12 col-md-3">
            <q-select
              v-model="filterCursoId"
              :options="cursos"
              option-label="nombre"
              option-value="id"
              label="Curso"
              outlined dense clearable emit-value map-options
              @update:model-value="obtenerAsignaciones"
            />
          </div>

          <div class="col-12 col-md-2">
            <q-input
              v-model="filterParalelo"
              label="Paralelo"
              outlined dense clearable
              @update:model-value="debouncedFetch"
            />
          </div>
        </div>
      </q-card-section>
    </q-card>
    <q-table
      :rows="asignaciones"
      :columns="columns"
      flat
      bordered
      dense
      wrap-cells
      :rows-per-page-options="[0]"
      title="Asignaciones"
      :filter="filter"
    >

      <!-- Acciones derecha -->
      <template v-slot:top-right>
        <q-btn
          label="Nueva"
          icon="add_circle_outline"
          color="primary"
          :loading="loading"
          no-caps
          class="q-mr-md"
          @click="$router.push('/asignaciones/crear')"
        />
        <q-btn
          icon="refresh"
          color="secondary"
          :loading="loading"
          no-caps
          @click="obtenerAsignaciones"
        />
        <q-input
          v-model="filter"
          label="Buscar"
          dense outlined class="q-ml-sm"
          clearable
          @update:model-value="debouncedFetch"
        >
          <template v-slot:append><q-icon name="search" /></template>
        </q-input>
      </template>

      <!-- Columna de acciones -->
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn-dropdown label="Acciones" color="primary" size="sm" no-caps>
            <q-list>
              <q-item clickable @click="$router.push(`/asignaciones/${props.row.id}/editar`)" v-close-popup>
                <q-item-section avatar><q-icon name="edit" /></q-item-section>
                <q-item-section>Editar</q-item-section>
              </q-item>
              <q-item clickable @click="abrirAsignacionEstudiantes(props.row)" v-close-popup>
                <q-item-section avatar><q-icon name="group_add" /></q-item-section>
                <q-item-section>Agregar Estudiantes</q-item-section>
              </q-item>
              <q-item clickable @click="eliminarAsignacion(props.row.id)" v-close-popup>
                <q-item-section avatar><q-icon name="delete" /></q-item-section>
                <q-item-section>Eliminar</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>
    </q-table>

    <!-- Diálogo estudiantes -->
    <q-dialog v-model="dialogEstudiantes" persistent full-width full-height>
      <q-card>
        <q-card-section class="row items-center">
          <div class="text-bold">Estudiantes de {{ asignacion.curso?.nombre }}</div>
          <q-space />
          <q-btn icon="close" flat v-close-popup />
        </q-card-section>

        <q-card-section>
          <div class="row">
            <div class="col-12 col-md-6">
              <q-input
                v-model="estudianteFilter"
                label="Buscar Estudiante"
                dense outlined class="q-mb-sm"
                @update:model-value="filtrarEstudiantes"
              >
                <template v-slot:append><q-icon name="search" /></template>
              </q-input>

              <q-markup-table bordered flat dense wrap-cells>
                <thead>
                <tr class="bg-primary text-white">
                  <th class="text-left">#</th>
                  <th class="text-left">Nombre</th>
                  <th class="text-left">Agregar</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(estudiante,i) in estudiantes" :key="estudiante.id">
                  <td>{{ i + 1 }}</td>
                  <td>{{ estudiante.nombre }}</td>
                  <td>
                    <q-btn
                      icon="add_circle_outline"
                      color="primary"
                      size="xs"
                      dense no-caps
                      :loading="loading"
                      label="Agregar"
                      @click="agregarEstudiante(estudiante)"
                    />
                  </td>
                </tr>
                </tbody>
              </q-markup-table>
            </div>

            <div class="col-12 col-md-6">
              <q-markup-table bordered flat dense wrap-cells>
                <thead>
                <tr class="bg-grey-3">
                  <th class="text-left">#</th>
                  <th class="text-left">Nombre</th>
                  <th class="text-left">Quitar</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(estudiante,i) in estudiantesSeleccionados" :key="estudiante.id">
                  <td>{{ i + 1 }}</td>
                  <td>{{ estudiante.nombre }}</td>
                  <td>
                    <q-btn
                      icon="remove_circle_outline"
                      color="negative"
                      size="xs"
                      dense no-caps
                      :loading="loading"
                      label="Quitar"
                      @click="quitarEstudiante(estudiante.id)"
                    />
                  </td>
                </tr>
                </tbody>
              </q-markup-table>
            </div>
          </div>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cerrar" color="negative" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
export default {
  name: 'AsignacionesPage',
  data () {
    return {
      // data
      asignaciones: [],
      asignacion: {},

      // ui state
      loading: false,
      dialogEstudiantes: false,

      // filtros
      filter: '',
      filterGestion: null,
      filterDocenteId: null,
      filterCursoId: null,
      filterParalelo: '',

      gestiones: [],

      // catálogos
      docentes: [],
      cursos: [],

      // tabla
      columns: [
        { name: 'actions', label: 'Acciones', align: 'center' },
        { name: 'docente_id', label: 'Docente', field: row => row.docente?.nombre, align: 'left' },
        { name: 'curso_id', label: 'Curso', field: row => row.curso?.nombre, align: 'left' },
        { name: 'unidadEducativa', label: 'Unidad Educativa', field: 'unidadEducativa', align: 'left' },
        { name: 'taller', label: 'Taller', field: 'taller', align: 'left' },
        { name: 'fases', label: 'Fases', field: 'fases', align: 'left' },
        { name: 'docentesEncargados', label: 'Docentes Encargados', field: 'docentesEncargados', align: 'left' },
        { name: 'anioFormacion', label: 'Año de Formación', field: 'anioFormacion', align: 'left' },
        { name: 'gestion', label: 'Gestión', field: 'gestion', align: 'left' },
        { name: 'user_id', label: 'Usuario', field: row => row.user?.name, align: 'left' }
      ],

      // estudiantes (diálogo)
      estudiantes: [],
      estudiantesAll: [],
      estudianteFilter: '',
      estudiantesSeleccionados: [],

      _debounceId: null
    }
  },

  async mounted () {
    // cargar
    this.obtenerAsignaciones()
    // catálogos
    this.$axios.get('cursos').then(res => { this.cursos = res.data })
    this.$axios.get('docentes').then(res => { this.docentes = res.data })
    await this.$axios.get('estudiantes').then(res => { this.estudiantes = res.data })
    this.estudiantesAll = [...this.estudiantes]

    // gestiones
    const y = new Date().getFullYear()
    for (let i = y - 3; i <= y + 3; i++) this.gestiones.push(String(i))


  },

  methods: {
    debouncedFetch () {
      clearTimeout(this._debounceId)
      this._debounceId = setTimeout(() => this.obtenerAsignaciones(), 350)
    },

    obtenerAsignaciones () {
      this.loading = true
      this.$axios.get('asignaciones', {
        params: {
          gestion: this.filterGestion || undefined,
          docente_id: this.filterDocenteId || undefined,
          curso_id: this.filterCursoId || undefined,
          paralelo: this.filterParalelo || undefined,  // cambia a 'taller' si no tienes columna
          search: this.filter || undefined,
          with_estudiantes: false
          // per_page: 50, // activa si quieres paginar en back
        }
      })
        .then(res => {
          // si usas paginate: this.asignaciones = res.data.data
          this.asignaciones = res.data
        })
        .catch(err => {
          this.$alert.error(err.response?.data?.message || 'Error al obtener asignaciones')
        })
        .finally(() => { this.loading = false })
    },

    abrirAsignacionEstudiantes (asignacion) {
      this.asignacion = asignacion
      // si quieres refrescar estudiantes actuales con pivot ids, puedes pedir with_estudiantes=true aquí
      this.estudiantesSeleccionados = asignacion.estudiantes ? [...asignacion.estudiantes] : []
      this.dialogEstudiantes = true
    },

    filtrarEstudiantes () {
      const q = (this.estudianteFilter || '').toLowerCase()
      this.estudiantes = !q
        ? [...this.estudiantesAll]
        : this.estudiantesAll.filter(e => (e.nombre || '').toLowerCase().includes(q))
    },

    agregarEstudiante (estudiante) {
      this.loading = true
      this.$axios.post('asignacion-estudiantes', {
        estudiante_id: estudiante.id,
        asignacion_id: this.asignacion.id
      })
        .then(() => {
          this.$alert.success('Estudiante agregado a la asignación')
          this.estudiantesSeleccionados.push(estudiante)
          this.filtrarEstudiantes()
        })
        .catch(err => this.$alert.error(err.response?.data?.message || 'Error al agregar estudiante'))
        .finally(() => { this.loading = false })
    },

    quitarEstudiante (estudiante_id) {
      this.loading = true
      const relacion = this.asignacion.estudiantes?.find(e => e.id === estudiante_id || e.estudiante_id === estudiante_id)
      if (!relacion) {
        this.$alert.error('No se encontró la relación para eliminar')
        this.loading = false
        return
      }
      this.$axios.delete(`asignacion-estudiantes-by-id/${relacion.pivot?.id || relacion.id}`)
        .then(() => {
          this.$alert.success('Estudiante eliminado')
          this.estudiantesSeleccionados = this.estudiantesSeleccionados.filter(e => e.id !== estudiante_id)
          this.filtrarEstudiantes()
        })
        .catch(err => this.$alert.error(err.response?.data?.message || 'Error al quitar estudiante'))
        .finally(() => { this.loading = false })
    },

    eliminarAsignacion (id) {
      this.$alert.dialog('¿Desea eliminar esta asignación?').onOk(() => {
        this.loading = true
        this.$axios.delete(`asignaciones/${id}`)
          .then(() => {
            this.$alert.success('Asignación eliminada')
            this.obtenerAsignaciones()
          })
          .catch(err => this.$alert.error(err.response?.data?.message || 'Error al eliminar'))
          .finally(() => { this.loading = false })
      })
    }
  }
}
</script>
