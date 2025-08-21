<template>
  <q-page class="q-pa-md">
    <!-- Header -->
    <div class="row items-center q-mb-md">
      <div class="col">
        <div class="text-h5 text-bold text-primary">Detalle del Curso</div>
      </div>
      <div class="col-auto q-gutter-sm">
        <q-btn flat icon="arrow_back" label="Volver" no-caps @click="$router.back()" />
        <q-btn outline color="primary" icon="how_to_reg" label="Tomar asistencia" no-caps
               @click="$router.push(`/curso/${$route.params.id}/asistencia`)" />
        <q-btn color="primary" icon="picture_as_pdf" label="Reporte PDF" no-caps
               @click="imprimirRegistro" :disable="!asignacion" />
      </div>
    </div>

    <div v-if="loading" class="flex flex-center">
      <q-spinner-dots color="primary" size="40px" />
    </div>

    <div v-else>
      <!-- Card info -->
      <q-card flat bordered class="q-mb-md">
        <q-card-section>
          <div class="text-h6">{{ asignacion?.curso?.nombre }}</div>
          <div class="text-subtitle2">{{ asignacion?.curso?.descripcion }}</div>
          <div class="q-mt-sm">
            <q-badge color="orange" outline class="q-mr-sm">Formación: {{ asignacion?.curso?.formacion }}</q-badge>
            <q-badge color="teal" outline>Tipo: {{ asignacion?.curso?.tipo }}</q-badge>
          </div>
          <div class="text-caption q-mt-sm">
            Unidad Educativa: <strong>{{ asignacion?.unidadEducativa }}</strong> |
            Gestión: <strong>{{ asignacion?.gestion }}</strong> |
            Año: <strong>{{ asignacion?.anioFormacion }}</strong> |
            Docente: <strong>{{ asignacion?.docente?.nombre }}</strong>
          </div>
        </q-card-section>
      </q-card>

      <!-- Filtros de rango (opcional) -->
      <q-card flat bordered class="q-mb-md">
        <q-card-section class="row q-col-gutter-md items-center">
          <div class="col-12 col-md-3">
            <q-input v-model="desde" label="Desde (YYYY-MM-DD)" dense outlined mask="date">
              <template #prepend><q-icon name="event" /></template>
              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                <q-date v-model="desde" />
              </q-popup-proxy>
            </q-input>
          </div>
          <div class="col-12 col-md-3">
            <q-input v-model="hasta" label="Hasta (YYYY-MM-DD)" dense outlined mask="date">
              <template #prepend><q-icon name="event" /></template>
              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                <q-date v-model="hasta" />
              </q-popup-proxy>
            </q-input>
          </div>
          <div class="col-12 col-md-3">
            <q-btn outline color="primary" label="Actualizar resumen" @click="cargarResumen" :loading="loadingResumen" />
          </div>
        </q-card-section>
      </q-card>

      <!-- Tabla (q-markup-table) -->
      <q-markup-table flat bordered dense wrap-cells>
        <thead>
        <tr class="bg-grey-2">
          <th class="text-left">#</th>
          <th class="text-left">Nombre</th>
          <th class="text-left">CI</th>
          <th class="text-right">Presentes</th>
          <th class="text-right">Tardes</th>
          <th class="text-right">Ausentes</th>
          <th class="text-right">Licencias</th>
          <th class="text-right">Total</th>
          <th class="text-right">% Asistencia</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="rowsEstudiantes.length === 0">
          <td colspan="9" class="text-grey-7">
            <q-icon name="warning" class="q-mr-sm" />
            Sin datos disponibles
          </td>
        </tr>
        <tr v-for="(e, i) in rowsEstudiantes" :key="e.id">
          <td>{{ i + 1 }}</td>
          <td>{{ e.nombre }}</td>
          <td>{{ e.ci }}</td>
          <td class="text-right">{{ (resumen[e.id]?.presentes ?? 0) }}</td>
          <td class="text-right">{{ (resumen[e.id]?.tardes ?? 0) }}</td>
          <td class="text-right">{{ (resumen[e.id]?.ausentes ?? 0) }}</td>
          <td class="text-right">{{ (resumen[e.id]?.licencias ?? 0) }}</td>
          <td class="text-right">{{ (resumen[e.id]?.total ?? 0) }}</td>
          <td class="text-right">
            {{ (resumen[e.id]?.porcentaje ?? 0) }}%
          </td>
        </tr>
        </tbody>
      </q-markup-table>
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'DetalleCursoPage',
  data () {
    return {
      asignacion: null,
      loading: true,
      loadingResumen: false,
      // resumen por estudiante_id
      resumen: {}, // { [estudiante_id]: {presentes,tardes,ausentes,licencias,total,porcentaje} }
      // rango opcional
      desde: null,
      hasta: null
    }
  },
  computed: {
    rowsEstudiantes () {
      const list = this.asignacion?.estudiantes
      return Array.isArray(list) ? list : []
    }
  },
  async mounted () {
    await this.obtenerDetalle()
    await this.cargarResumen()
  },
  methods: {
    normalizar (raw) {
      if (!raw || typeof raw !== 'object') return { curso: {}, docente: {}, estudiantes: [] }
      return {
        ...raw,
        curso: raw.curso || {},
        docente: raw.docente || {},
        estudiantes: Array.isArray(raw.estudiantes) ? raw.estudiantes : []
      }
    },
    obtenerDetalle () {
      this.loading = true
      return this.$axios.get(`/asignaciones/${this.$route.params.id}`, {
        params: { with_estudiantes: true }
      })
        .then(res => { this.asignacion = this.normalizar(res.data) })
        .catch(() => {
          this.asignacion = this.normalizar(null)
          this.$alert.error('Error al obtener datos del curso')
        })
        .finally(() => { this.loading = false })
    },
    cargarResumen () {
      if (!this.$route.params.id) return
      this.loadingResumen = true
      return this.$axios.get('/asistencias/resumen', {
        params: {
          asignacion_id: this.$route.params.id,
          desde: this.desde || undefined,
          hasta: this.hasta || undefined
        }
      })
        .then(res => { this.resumen = res.data.items || {} })
        .catch(() => { this.$alert.error('No se pudo cargar el resumen de asistencia') })
        .finally(() => { this.loadingResumen = false })
    },
    imprimirRegistro () {
      const id = this.$route.params.id
      window.open(`${this.$axios.defaults.baseURL}/asignaciones/${id}/reporte`, '_blank')
    }
  }
}
</script>
