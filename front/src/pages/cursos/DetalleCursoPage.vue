<template>
  <q-page class="q-pa-md">
    <!-- Header -->
    <div class="row items-center q-mb-md">
      <div class="col">
        <div class="text-h5 text-bold text-primary">Detalle del Curso</div>
      </div>

      <div class="col-auto q-gutter-sm">
        <q-btn flat icon="arrow_back" label="Volver" no-caps @click="$router.back()" />

        <!-- Acciones -->
        <q-btn-dropdown color="primary" outline icon="manage_accounts" label="Acciones" no-caps>
          <q-list>
            <q-item clickable v-close-popup @click="$router.push(`/curso/${$route.params.id}/asistencia`)">
              <q-item-section avatar><q-icon name="how_to_reg" /></q-item-section>
              <q-item-section>Tomar asistencia</q-item-section>
            </q-item>
<!--            ir a notas-->
            <q-item clickable v-close-popup @click="$router.push(`/curso/${$route.params.id}/notas`)">
              <q-item-section avatar><q-icon name="edit_note" /></q-item-section>
              <q-item-section>Editar notas</q-item-section>
            </q-item>
            <q-item clickable v-close-popup @click="scrollToNotas">
              <q-item-section avatar><q-icon name="grading" /></q-item-section>
              <q-item-section>Ir a notas</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>

        <!-- Reportes -->
        <q-btn-dropdown color="primary" icon="picture_as_pdf" label="Reportes" no-caps :disable="!asignacion">
          <q-list>
            <q-item clickable v-close-popup @click="openReporte('registro')">
              <q-item-section avatar><q-icon name="table_chart" /></q-item-section>
              <q-item-section>Registro (E1..E12)</q-item-section>
            </q-item>
            <q-item clickable v-close-popup @click="openReporte('notas')">
              <q-item-section avatar><q-icon name="grading" /></q-item-section>
              <q-item-section>Notas (SER/SABER/HACER/DECIDIR)</q-item-section>
            </q-item>
            <q-item clickable v-close-popup @click="openReporte('asistencia')">
              <q-item-section avatar><q-icon name="event_available" /></q-item-section>
              <q-item-section>Asistencia</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
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

      <!-- ===== Notas (visible en principal) ===== -->
      <q-card ref="cardNotas" flat bordered class="q-mb-md">
        <q-card-section class="row items-center">
          <div class="text-subtitle1">Notas (SER · SABER · HACER · DECIDIR)</div>
          <q-space />
          <q-btn flat dense icon="refresh" @click="cargarNotas" :loading="loadingNotas" />
        </q-card-section>

        <q-markup-table flat bordered dense wrap-cells>
          <thead>
          <tr class="bg-grey-2">
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-right">SER (10)</th>
            <th class="text-right">SABER (35)</th>
            <th class="text-right">HACER (35)</th>
            <th class="text-right">DECIDIR (10)</th>
            <th class="text-right">TOTAL (90)</th>
          </tr>
          </thead>
          <tbody>
          <tr v-if="rowsEstudiantes.length === 0">
            <td colspan="7" class="text-grey-7">
              <q-icon name="warning" class="q-mr-sm" />
              Sin estudiantes
            </td>
          </tr>
          <tr v-for="(e,i) in rowsEstudiantes" :key="e.id">
            <td>{{ i + 1 }}</td>
            <td>{{ e.nombre }}</td>
            <td class="text-right">{{ nota(e.id,'ser') }}</td>
            <td class="text-right">{{ nota(e.id,'saber') }}</td>
            <td class="text-right">{{ nota(e.id,'hacer') }}</td>
            <td class="text-right">{{ nota(e.id,'decidir') }}</td>
            <td class="text-right text-weight-medium">{{ totalNota(e.id) }}</td>
          </tr>
          </tbody>
        </q-markup-table>
      </q-card>

      <!-- ===== Asistencia: filtros de rango ===== -->
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

      <!-- Asistencia (resumen) -->
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
          <td class="text-right">{{ (resumen[e.id]?.porcentaje ?? 0) }}%</td>
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

      // Notas
      loadingNotas: false,
      notas: {}, // { [estudiante_id]: { ser, saber, hacer, decidir, total? } }

      // Asistencia
      loadingResumen: false,
      resumen: {}, // { [estudiante_id]: {presentes,tardes,ausentes,licencias,total,porcentaje} }
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
    await Promise.all([ this.cargarNotas(), this.cargarResumen() ])
  },
  methods: {
    // ===== Navegación / Reportes
    scrollToNotas () {
      const el = this.$refs.cardNotas?.$el || this.$refs.cardNotas
      if (el && el.scrollIntoView) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
    },
    openReporte (tipo) {
      const id = this.$route.params.id
      const base = this.$axios.defaults.baseURL
      if (tipo === 'registro') window.open(`${base}/asignaciones/${id}/reporte-evaluaciones`, '_blank')
      if (tipo === 'notas')    window.open(`${base}/asignaciones/${id}/reporte-notas`, '_blank')
      if (tipo === 'asistencia') {
        const params = new URLSearchParams()
        if (this.desde) params.append('desde', this.desde)
        if (this.hasta) params.append('hasta', this.hasta)
        const qs = params.toString() ? `?${params.toString()}` : ''
        window.open(`${base}/asignaciones/${id}/reporte-asistencia${qs}`, '_blank')
      }
    },

    // ===== Carga base
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

    // ===== Notas (SER/SABER/HACER/DECIDIR)
    async cargarNotas () {
      this.loadingNotas = true
      try {
        const { data } = await this.$axios.get('/notas', {
          params: { asignacion_id: this.$route.params.id }
        })
        // Tolerar {items:{}} o array []
        const raw = data?.items ?? data ?? []
        if (Array.isArray(raw)) {
          const map = {}
          raw.forEach(n => { if (n?.estudiante_id) map[n.estudiante_id] = n })
          this.notas = map
        } else {
          this.notas = raw || {}
        }
      } catch (e) {
        this.$alert.error('No se pudieron cargar las notas')
      } finally {
        this.loadingNotas = false
      }
    },
    nota (id, campo) {
      const n = this.notas[id] || {}
      return Number.isFinite(+n[campo]) ? +n[campo] : 0
    },
    totalNota (id) {
      const n = this.notas[id] || {}
      const t = (+(n.ser || 0)) + (+(n.saber || 0)) + (+(n.hacer || 0)) + (+(n.decidir || 0))
      return Number.isFinite(t) ? t : 0
    },

    // ===== Asistencia (resumen)
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
    }
  }
}
</script>
