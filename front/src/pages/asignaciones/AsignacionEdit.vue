<template>
  <q-page class="q-pa-md">
    <q-card flat bordered class="q-pa-md">

      <!-- Encabezado -->
      <div class="row items-center q-mb-md">
        <div class="col">
          <div class="text-h6">Editar Asignación</div>
          <div class="text-caption text-grey-7">Actualiza los datos y guarda los cambios</div>
        </div>
        <div class="col-auto">
          <q-btn flat color="negative" icon="close" label="Cancelar" no-caps @click="$router.push('/asignaciones')" />
        </div>
      </div>

      <q-separator spaced />

      <!-- Formulario -->
      <q-form @submit="guardarAsignacion" class="q-gutter-md">

        <!-- Datos académicos -->
        <div class="text-subtitle2 text-grey-8">Datos académicos</div>
        <div class="row ">
          <div class="col-12 col-md-4">
            <q-input
              v-model="asignacion.anioFormacion"
              label="Año de Formación"
              dense outlined
              :rules="[v => !!v || 'Requerido']"
            >
              <template #prepend><q-icon name="school" /></template>
            </q-input>
          </div>

          <div class="col-12 col-md-4">
            <q-select
              v-model="asignacion.gestion"
              :options="gestiones"
              label="Gestión"
              dense outlined emit-value map-options
              :rules="[v => !!v || 'Requerido']"
            >
              <template #prepend><q-icon name="event" /></template>
            </q-select>
          </div>

          <div class="col-12 col-md-4">
            <q-select
              v-model="asignacion.curso_id"
              :options="cursos"
              option-label="nombre"
              option-value="id"
              label="Unidad de Formación (Curso)"
              dense outlined emit-value map-options
              :rules="[v => !!v || 'Requerido']"
            >
              <template #prepend><q-icon name="menu_book" /></template>
            </q-select>
          </div>
        </div>

        <!-- Docencia -->
        <div class="text-subtitle2 text-grey-8 q-mt-md">Docencia</div>
        <div class="row ">
          <div class="col-12 col-md-6">
            <q-select
              v-model="asignacion.docente_id"
              :options="docentes"
              option-label="nombre"
              option-value="id"
              label="Docente"
              dense outlined emit-value map-options
              :rules="[v => !!v || 'Requerido']"
            >
              <template #prepend><q-icon name="person" /></template>
            </q-select>
          </div>

          <div class="col-12 col-md-6">
            <q-input
              v-model="asignacion.unidadEducativa"
              label="Unidad Educativa"
              dense outlined
              :rules="[v => !!v || 'Requerido']"
            >
              <template #prepend><q-icon name="apartment" /></template>
            </q-input>
          </div>
        </div>

        <!-- Detalle -->
        <div class="text-subtitle2 text-grey-8 q-mt-md">Detalle</div>
        <div class="row ">
          <div class="col-12 col-md-4">
            <q-input v-model="asignacion.taller" label="Taller" dense outlined>
              <template #prepend><q-icon name="category" /></template>
            </q-input>
          </div>
          <div class="col-12 col-md-4">
            <q-input v-model="asignacion.fases" label="Fases" dense outlined>
              <template #prepend><q-icon name="timeline" /></template>
            </q-input>
          </div>
          <div class="col-12 col-md-4">
            <q-input v-model="asignacion.docentesEncargados" label="Docentes Encargados" dense outlined>
              <template #prepend><q-icon name="groups" /></template>
            </q-input>
          </div>
        </div>

        <!-- Acciones -->
        <div class="row justify-end q-gutter-sm q-mt-lg">
          <q-btn flat color="negative" icon="close" label="Cancelar" no-caps @click="$router.push('/asignaciones')" />
          <q-btn color="primary" icon="save" label="Guardar cambios" no-caps type="submit" :loading="loading" />
        </div>
      </q-form>

      <q-inner-loading :showing="loading">
        <q-spinner-dots size="32px" />
      </q-inner-loading>
    </q-card>
  </q-page>
</template>

<script>
export default {
  name: 'AsignacionEdit',
  // props: ['id'],
  data () {
    return {
      asignacion: {
        curso_id: null,
        docente_id: null,
        unidadEducativa: '',
        taller: '',
        fases: '',
        docentesEncargados: '',
        anioFormacion: '',
        gestion: null
      },
      cursos: [],
      docentes: [],
      gestiones: [],
      loading: false
    }
  },
  async mounted () {
    this.loading = true
    try {
      // catálogos
      const [cursosRes, docentesRes] = await Promise.all([
        this.$axios.get('cursos'),
        this.$axios.get('docentes')
      ])
      this.cursos = cursosRes.data
      this.docentes = docentesRes.data

      // asignación
      const { data } = await this.$axios.get(`asignacionesFind/${parseInt(this.$route.params.id)}`)
      this.asignacion = this.normalizeAsignacion(data)
    } catch (err) {
      this.$alert.error(err.response?.data?.message || 'No se pudo cargar la asignación')
    } finally {
      this.loading = false
    }

    // opciones de gestión (label/value como string)
    const y = new Date().getFullYear()
    for (let i = y - 3; i <= y + 3; i++) {
      this.gestiones.push({ label: String(i), value: String(i) })
    }
  },
  methods: {
    normalizeAsignacion (raw) {
      // Si el backend devuelve relaciones, garantizamos *_id y gestion como string
      const out = { ...raw }
      if (!out.curso_id && raw.curso) out.curso_id = raw.curso.id
      if (!out.docente_id && raw.docente) out.docente_id = raw.docente.id
      if (out.gestion != null) out.gestion = String(out.gestion)
      return out
    },
    guardarAsignacion () {
      this.loading = true
      this.$axios.put(`asignaciones/${parseInt(this.$route.params.id)}`, this.asignacion)
        .then(() => {
          this.$alert.success('Asignación actualizada')
          this.$router.push('/asignaciones')
        })
        .catch(err => this.$alert.error(err.response?.data?.message || 'Error al actualizar'))
        .finally(() => { this.loading = false })
    }
  }
}
</script>

<style scoped>
.text-subtitle2 { letter-spacing: .2px; }
</style>
