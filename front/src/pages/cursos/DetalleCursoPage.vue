<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <div class="col">
        <div class="text-h5 text-bold text-primary">Detalle del Curso</div>
      </div>
      <div class="col-auto q-gutter-sm">
        <q-btn flat icon="arrow_back" label="Volver" no-caps @click="$router.back()" />
        <q-btn color="primary" icon="picture_as_pdf" label="Reporte PDF" no-caps @click="imprimirRegistro" :disable="!asignacion" />
      </div>
    </div>

    <div v-if="loading" class="flex flex-center">
      <q-spinner-dots color="primary" size="40px" />
    </div>

    <div v-else>
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

      <q-table
        :rows="rowsEstudiantes"
        :columns="columns"
        row-key="id"
        title="Lista de Estudiantes"
        flat
        bordered
        dense
        :rows-per-page-options="[0]"
      >
        <template #body-cell-nro="props">
          <q-td :props="props">{{ props.rowIndex + 1 }}</q-td>
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'DetalleCursoPage',
  data () {
    return {
      asignacion: null,   // puede ser null; la tabla usa computed seguro
      loading: true,
      columns: [
        { name: 'nro', label: '#', align: 'left', field: '__nro' },
        { name: 'nombre', label: 'Nombre', field: 'nombre', align: 'left' },
        { name: 'ci', label: 'CI', field: 'ci', align: 'left' },
      ]
    }
  },
  computed: {
    rowsEstudiantes () {
      // NUNCA devolver undefined a QTable
      const list = this.asignacion?.estudiantes
      return Array.isArray(list) ? list : []
    }
  },
  mounted () { this.obtenerDetalle() },
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
      this.$axios.get(`/asignaciones/${this.$route.params.id}`, {
        params: { with_estudiantes: true }   // <- IMPORTANTE
      })
        .then(res => { this.asignacion = this.normalizar(res.data) })
        .catch(() => {
          this.asignacion = this.normalizar(null)
          this.$alert.error('Error al obtener datos del curso')
        })
        .finally(() => { this.loading = false })
    },
    imprimirRegistro () {
      const id = this.$route.params.id
      window.open(`${this.$axios.defaults.baseURL}/asignaciones/${id}/reporte`, '_blank')
    }
  }
}
</script>
