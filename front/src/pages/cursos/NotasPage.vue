<template>
  <q-page class="q-pa-md">
    <!-- Header -->
    <div class="row items-center q-mb-md">
      <div class="col">
        <div class="text-h5 text-bold text-primary">Detalle del Curso</div>
      </div>
      <div class="col-auto q-gutter-sm">
        <q-btn flat icon="arrow_back" label="Volver" no-caps @click="$router.push('/curso/' + $route.params.id)" />
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
      <!-- Info del curso -->
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

      <!-- === EVALUACIONES (SER/SABER/HACER/DECIDIR) === -->
      <q-card flat bordered>
        <q-card-section class="row items-center">
          <div class="col">
            <div class="text-subtitle1 text-bold">Evaluaciones (SER · SABER · HACER · DECIDIR)</div>
            <div class="text-caption text-grey-7">Máximos: SER 10, SABER 35, HACER 35, DECIDIR 10 — Total 90</div>
          </div>
          <div class="col-auto q-gutter-sm">
            <q-toggle v-model="editNotas" color="primary" label="Editar" />
            <q-btn :disable="!editNotas || savingNotas" color="primary" icon="save"
                   label="Guardar" no-caps @click="guardarNotasBatch" :loading="savingNotas" />
          </div>
        </q-card-section>

        <q-separator />

        <q-markup-table flat bordered dense wrap-cells>
          <thead>
          <tr class="bg-grey-2">
            <th class="text-left">#</th>
            <th class="text-left">Nombre</th>
            <th class="text-right">SER (10)</th>
            <th class="text-right">SABER (35)</th>
            <th class="text-right">HACER (35)</th>
            <th class="text-right">DECIDIR (10)</th>
            <th class="text-right">Total (90)</th>
          </tr>
          </thead>
          <tbody>
          <tr v-if="rowsEstudiantes.length === 0">
            <td colspan="7" class="text-grey-7">
              <q-icon name="warning" class="q-mr-sm" />
              Sin estudiantes
            </td>
          </tr>
          <tr v-for="(e, i) in rowsEstudiantes" :key="e.id">
            <td>{{ i + 1 }}</td>
            <td>{{ e.nombre }}</td>

            <!-- SER -->
            <td class="text-right">
              <template v-if="editNotas">
                <q-input v-model.number="edicionNotas[e.id].ser" type="number" dense outlined
                         class="w-90" :min="0" :max="10" @update:model-value="clamp(e.id)" />
              </template>
              <template v-else>{{ (notas[e.id]?.ser ?? 0) }}</template>
            </td>

            <!-- SABER -->
            <td class="text-right">
              <template v-if="editNotas">
                <q-input v-model.number="edicionNotas[e.id].saber" type="number" dense outlined
                         class="w-90" :min="0" :max="35" @update:model-value="clamp(e.id)" />
              </template>
              <template v-else>{{ (notas[e.id]?.saber ?? 0) }}</template>
            </td>

            <!-- HACER -->
            <td class="text-right">
              <template v-if="editNotas">
                <q-input v-model.number="edicionNotas[e.id].hacer" type="number" dense outlined
                         class="w-90" :min="0" :max="35" @update:model-value="clamp(e.id)" />
              </template>
              <template v-else>{{ (notas[e.id]?.hacer ?? 0) }}</template>
            </td>

            <!-- DECIDIR -->
            <td class="text-right">
              <template v-if="editNotas">
                <q-input v-model.number="edicionNotas[e.id].decidir" type="number" dense outlined
                         class="w-90" :min="0" :max="10" @update:model-value="clamp(e.id)" />
              </template>
              <template v-else>{{ (notas[e.id]?.decidir ?? 0) }}</template>
            </td>

            <!-- TOTAL -->
            <td class="text-right">
              {{ totalLocal(e.id) }}
            </td>
          </tr>
          </tbody>
        </q-markup-table>

        <q-inner-loading :showing="loadingNotas">
          <q-spinner-dots size="32px" />
        </q-inner-loading>
      </q-card>
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

      // NOTAS
      loadingNotas: false,
      savingNotas: false,
      editNotas: false,
      notas: {},          // { [estudiante_id]: {ser,saber,hacer,decidir,total} }
      edicionNotas: {},   // copia editable

      // (tu bloque de resumen asistencia puede quedarse igual si lo necesitas)
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
    await this.cargarNotas()
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

    /* === NOTAS === */
    cargarNotas () {
      this.loadingNotas = true
      return this.$axios.get('/notas', {
        params: { asignacion_id: this.$route.params.id }
      })
        .then(res => {
          this.notas = res.data.items || {}
          // preparar edición con valores actuales o ceros
          this.edicionNotas = {}
          this.rowsEstudiantes.forEach(e => {
            const n = this.notas[e.id] || { ser:0, saber:0, hacer:0, decidir:0 }
            this.edicionNotas[e.id] = { ser:n.ser, saber:n.saber, hacer:n.hacer, decidir:n.decidir }
          })
        })
        .catch(() => this.$alert.error('No se pudieron cargar las notas'))
        .finally(() => { this.loadingNotas = false })
    },
    clamp (id) {
      const n = this.edicionNotas[id]
      if (!n) return
      n.ser     = Math.max(0, Math.min(10, Number(n.ser || 0)))
      n.saber   = Math.max(0, Math.min(35, Number(n.saber || 0)))
      n.hacer   = Math.max(0, Math.min(35, Number(n.hacer || 0)))
      n.decidir = Math.max(0, Math.min(10, Number(n.decidir || 0)))
    },
    totalLocal (id) {
      const n = this.editNotas ? this.edicionNotas[id] : this.notas[id]
      if (!n) return 0
      return (Number(n.ser||0) + Number(n.saber||0) + Number(n.hacer||0) + Number(n.decidir||0))
    },
    guardarNotasBatch () {
      const items = this.rowsEstudiantes.map(e => ({
        estudiante_id: e.id,
        ser:     this.edicionNotas[e.id]?.ser ?? 0,
        saber:   this.edicionNotas[e.id]?.saber ?? 0,
        hacer:   this.edicionNotas[e.id]?.hacer ?? 0,
        decidir: this.edicionNotas[e.id]?.decidir ?? 0
      }))

      this.savingNotas = true
      this.$axios.post('/notas', {
        asignacion_id: this.$route.params.id,
        items
      })
        .then(() => {
          this.$alert.success('Notas guardadas')
          this.editNotas = false
          return this.cargarNotas()
        })
        .catch(err => this.$alert.error(err.response?.data?.message || 'Error al guardar notas'))
        .finally(() => { this.savingNotas = false })
    },

    /* === PDF === */
    imprimirRegistro () {
      const id = this.$route.params.id
      window.open(`${this.$axios.defaults.baseURL}/asignaciones/${id}/reporte`, '_blank')
    }
  }
}
</script>

<style scoped>
.w-90 { width: 90px; }
</style>
