<template>
  <q-page class="q-pa-md">
    <q-card flat bordered class="q-pa-md">

      <!-- Encabezado -->
      <div class="row items-center q-mb-md">
        <div class="col">
          <div class="text-h6">Nueva Asignación</div>
          <div class="text-caption text-grey-7">Complete los datos y presione Guardar</div>
        </div>
        <div class="col-auto">
          <q-btn flat color="negative" icon="close" label="Cancelar" no-caps @click="$router.push('/asignaciones')" />
        </div>
      </div>

      <q-separator spaced />

      <!-- Formulario -->
      <q-form @submit="guardarAsignacion" class="q-gutter-md">

        <!-- Bloque: Académico -->
        <div class="text-subtitle2 text-grey-8">Datos académicos</div>
        <div class="row q-col-gutter-md">
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

        <!-- Bloque: Docencia y UE -->
        <div class="text-subtitle2 text-grey-8 q-mt-md">Docencia</div>
        <div class="row q-col-gutter-md">
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

        <!-- Bloque: Detalle -->
        <div class="text-subtitle2 text-grey-8 q-mt-md">Detalle</div>
        <div class="row q-col-gutter-md">
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
          <q-btn color="primary" icon="save" label="Guardar" no-caps type="submit" :loading="loading" />
        </div>
      </q-form>

      <!-- Loading -->
      <q-inner-loading :showing="loading">
        <q-spinner-dots size="32px" />
      </q-inner-loading>
    </q-card>
  </q-page>
</template>

<script>
export default {
  name: 'AsignacionCreate',
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
    this.$axios.get('cursos').then(res => { this.cursos = res.data })
    this.$axios.get('docentes').then(res => { this.docentes = res.data })

    const y = new Date().getFullYear()
    // opciones como strings, p.ej. "2023"
    for (let i = y - 3; i <= y + 3; i++) {
      this.gestiones.push({ label: String(i), value: String(i) })
    }
  },
  methods: {
    guardarAsignacion () {
      this.loading = true
      this.$axios.post('asignaciones', this.asignacion)
        .then(() => {
          this.$alert.success('Asignación creada')
          this.$router.push('/asignaciones')
        })
        .catch(err => this.$alert.error(err.response?.data?.message || 'Error al crear'))
        .finally(() => { this.loading = false })
    }
  }
}
</script>

<style scoped>
/* pequeño toque visual para títulos de bloque */
.text-subtitle2 {
  letter-spacing: .2px;
}
</style>
