<template>
  <q-page class="q-pa-md">
    <!-- Header -->
    <div class="row items-center q-mb-md">
      <div class="col">
        <div class="text-h5 text-bold">Asistencia</div>
        <div class="text-caption text-grey-7">
          Curso: <b>{{ asignacion?.curso?.nombre || '-' }}</b> •
          Docente: <b>{{ asignacion?.docente?.nombre || '-' }}</b>
        </div>
      </div>
      <div class="col-auto q-gutter-sm">
        <q-btn flat icon="arrow_back" label="Volver" no-caps @click="$router.back()" />
      </div>
    </div>

    <!-- Filtros / Fecha -->
    <q-card flat bordered class="q-mb-md">
      <q-card-section class="row q-col-gutter-md items-center">
        <div class="col-12 col-md-3">
          <q-input v-model="fecha" label="Fecha" dense outlined mask="date" :rules="[v => !!v || 'Requerido']">
            <template #prepend><q-icon name="event" /></template>
            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
              <q-date v-model="fecha" :options="o => o <= hoy" />
            </q-popup-proxy>
          </q-input>
        </div>

        <div class="col-12 col-md-6">
          <q-btn outline color="primary" label="Marcar todos: Presente" dense @click="marcarTodos('Presente')" class="q-mr-sm" />
          <q-btn outline color="warning" label="Todos: Tarde" dense @click="marcarTodos('Tarde')" class="q-mr-sm" />
          <q-btn outline color="negative" label="Todos: Ausente" dense @click="marcarTodos('Ausente')" />
        </div>

        <div class="col-12 col-md-3 text-right">
          <q-btn color="primary" icon="save" label="Guardar" :loading="saving" @click="guardar" />
        </div>
      </q-card-section>
    </q-card>

    <!-- Tabla -->
    <q-table
      :rows="rowsEstudiantes"
      :columns="columns"
      row-key="id"
      flat bordered dense
      :rows-per-page-options="[0]"
      title="Lista de Estudiantes"
    >
      <template #body-cell-nro="props">
        <q-td :props="props">{{ props.rowIndex + 1 }}</q-td>
      </template>

      <template #body-cell-asistencia="props">
        <q-td :props="props" class="q-pa-xs">
          <q-btn-toggle
            v-model="asistencias[props.row.id]"
            :options="opciones"
            rounded unelevated dense
            toggle-color="primary"
          />
        </q-td>
      </template>
    </q-table>

    <q-inner-loading :showing="loading">
      <q-spinner-dots size="32px" />
    </q-inner-loading>
  </q-page>
</template>

<script>
export default {
  name: 'AsistenciaPage',
  data () {
    const hoy = new Date().toISOString().slice(0,10) // YYYY-MM-DD
    return {
      hoy,
      fecha: hoy,
      asignacion: null,
      loading: true,
      saving: false,
      asistencias: {}, // { estudiante_id: 'Presente' | 'Ausente' | 'Tarde' | 'Licencia' }
      columns: [
        { name: 'nro', label: '#', field: '__nro', align: 'left' },
        { name: 'nombre', label: 'Nombre', field: 'nombre', align: 'left' },
        { name: 'ci', label: 'CI', field: 'ci', align: 'left' },
        { name: 'asistencia', label: 'Asistencia', field: '__a', align: 'left' },
      ],
      opciones: [
        {label: 'P', value: 'Presente'},
        {label: 'T', value: 'Tarde'},
        {label: 'A', value: 'Ausente'},
        {label: 'L', value: 'Licencia'},
      ],
    }
  },
  computed: {
    rowsEstudiantes () {
      const list = this.asignacion?.estudiantes
      return Array.isArray(list) ? list : []
    }
  },
  watch: {
    fecha () { this.cargarAsistencias() }
  },
  async mounted () {
    await this.cargarAsignacion()
    await this.cargarAsistencias()
  },
  methods: {
    async cargarAsignacion () {
      this.loading = true
      try {
        const id = this.$route.params.id
        const { data } = await this.$axios.get(`/asignaciones/${id}`, { params: { with_estudiantes: true } })
        // normalizar para evitar undefined
        this.asignacion = {
          ...data,
          estudiantes: Array.isArray(data.estudiantes) ? data.estudiantes : []
        }
        // inicializa asistencias con 'Presente' por defecto si quieres
        this.asignacion.estudiantes.forEach(e => {
          if (!this.asistencias[e.id]) this.asistencias[e.id] = 'Presente'
        })
      } catch (e) {
        this.$alert.error('No se pudo cargar el curso')
      } finally {
        this.loading = false
      }
    },

    async cargarAsistencias () {
      if (!this.asignacion?.id || !this.fecha) return
      this.loading = true
      try {
        const { data } = await this.$axios.get('/asistencias', {
          params: { asignacion_id: this.asignacion.id, fecha: this.fecha }
        })
        const mapa = data.items || {}
        // aplica lo recibido; si falta alguno, conserva el valor actual o default
        this.asignacion.estudiantes.forEach(e => {
          this.asistencias[e.id] = mapa[e.id] || this.asistencias[e.id] || 'Presente'
        })
      } catch (e) {
        // si no hay registros ese día, dejamos los defaults
      } finally {
        this.loading = false
      }
    },

    marcarTodos (valor) {
      this.rowsEstudiantes.forEach(e => { this.asistencias[e.id] = valor })
    },

    async guardar () {
      if (!this.asignacion?.id || !this.fecha) return
      this.saving = true
      try {
        const items = this.rowsEstudiantes.map(e => ({
          estudiante_id: e.id,
          asistencia: this.asistencias[e.id] || 'Presente'
        }))
        await this.$axios.post('/asistencias', {
          asignacion_id: this.asignacion.id,
          fecha: this.fecha,
          items
        })
        this.$alert.success('Asistencias guardadas')
      } catch (e) {
        this.$alert.error(e.response?.data?.message || 'No se pudo guardar la asistencia')
      } finally {
        this.saving = false
      }
    }
  }
}
</script>
