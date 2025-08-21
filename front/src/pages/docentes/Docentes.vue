<template>
  <q-page class="q-pa-md">
    <q-card>
      <q-card-section>
          <div class="row q-col-gutter-sm">
            <div class="col-12 col-md-3">
              <q-input v-model="filter" label="Buscar por nombre/CI/email" dense outlined clearable @update:model-value="debouncedFetch">
                <template #append><q-icon name="search" /></template>
              </q-input>
            </div>
          </div>
      </q-card-section>
    </q-card>
    <q-table
      :rows="docentes"
      :columns="columns"
      flat bordered dense wrap-cells
      :rows-per-page-options="[0]"
      title="Docentes"
      :filter="filter"
    >

      <template #top-right>
        <q-btn label="Nuevo" icon="add_circle_outline" color="primary" @click="nuevoDocente" :loading="loading" no-caps />
      </template>

      <template #body-cell-actions="props">
        <q-td :props="props">
          <q-btn-dropdown label="Acciones" color="primary" size="sm" no-caps>
            <q-list>
              <q-item clickable @click="editarDocente(props.row)" v-close-popup>
                <q-item-section avatar><q-icon name="edit" /></q-item-section>
                <q-item-section>Editar</q-item-section>
              </q-item>

              <q-item v-if="props.row.user" clickable @click="resetPassword(props.row)" v-close-popup>
                <q-item-section avatar><q-icon name="vpn_key" /></q-item-section>
                <q-item-section>Resetear contraseña</q-item-section>
              </q-item>

              <q-item clickable @click="eliminarDocente(props.row.id)" v-close-popup>
                <q-item-section avatar><q-icon name="delete" /></q-item-section>
                <q-item-section>Eliminar</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>
    </q-table>

    <!-- Diálogo Crear/Editar -->
    <q-dialog v-model="dialog" persistent>
      <q-card style="width: 700px">
        <q-card-section class="row items-center">
          <div class="text-h6">{{ docente.id ? 'Editar' : 'Nuevo' }} Docente</div>
          <q-space />
          <q-btn flat icon="close" v-close-popup />
        </q-card-section>

        <q-separator />

        <q-card-section>
          <q-form @submit="guardarDocente" class="q-gutter-md">
            <div class="text-subtitle2 text-grey-8">Datos del docente</div>
            <div class="row ">
              <div class="col-12 col-md-6">
                <q-input v-model="docente.nombre" label="Nombre" dense outlined :rules="[v => !!v || 'Requerido']">
                  <template #prepend><q-icon name="person" /></template>
                </q-input>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="docente.ci" label="CI" dense outlined :rules="[v => !!v || 'Requerido']">
                  <template #prepend><q-icon name="badge" /></template>
                </q-input>
              </div>
              <div class="col-12 col-md-3">
                <q-input v-model="docente.telefono" label="Teléfono" dense outlined>
                  <template #prepend><q-icon name="call" /></template>
                </q-input>
              </div>
              <div class="col-12 col-md-6">
                <q-input v-model="docente.email" label="Email" dense outlined type="email">
                  <template #prepend><q-icon name="mail" /></template>
                </q-input>
              </div>
            </div>

            <q-separator spaced />

            <div class="row items-center">
              <div class="col">
                <div class="text-subtitle2 text-grey-8">Usuario de acceso</div>
                <div class="text-caption text-grey-7">
                  (Opcional) Crear o actualizar la cuenta de acceso vinculada al docente
                </div>
              </div>
              <div class="col-auto">
                <q-toggle v-model="crearUsuario" color="primary" label="Habilitar" />
              </div>
            </div>

            <div class="row " v-if="crearUsuario">
              <div class="col-12 col-md-4">
                <q-input v-model="usuario.username" label="Usuario" dense outlined :rules="[v => !!v || 'Requerido']">
                  <template #prepend><q-icon name="account_circle" /></template>
                  <template #append>
                    <q-btn dense flat icon="auto_fix_high" @click="autocompletarUsername" />
                  </template>
                </q-input>
              </div>
              <div class="col-12 col-md-4">
                <q-input v-model="usuario.email" label="Email de acceso" dense outlined type="email">
                  <template #prepend><q-icon name="alternate_email" /></template>
                </q-input>
              </div>
              <div class="col-12 col-md-4">
                <q-input v-model="usuario.password" label="Contraseña" dense outlined :type="verPass ? 'text' : 'password'">
                  <template #prepend><q-icon name="vpn_key" /></template>
                  <template #append>
                    <q-btn dense flat :icon="verPass ? 'visibility_off' : 'visibility'" @click="verPass = !verPass" />
                    <q-btn dense flat icon="auto_fix_high" @click="generarPassword" />
                  </template>
                </q-input>
              </div>
              <div class="col-12 col-md-4">
                <q-select v-model="usuario.role" :options="roles" label="Rol" dense outlined emit-value map-options />
              </div>
            </div>

            <div class="row justify-end q-gutter-sm q-mt-md">
              <q-btn flat label="Cancelar" color="negative" v-close-popup />
              <q-btn label="Guardar" type="submit" color="primary" :loading="loading" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
export default {
  name: 'DocentesPage',
  data () {
    return {
      docentes: [],
      docente: {},
      dialog: false,
      loading: false,
      filter: '',
      crearUsuario: false,
      usuario: {
        username: '',
        email: '',
        password: '',
        role: 'Docente'
      },
      verPass: false,
      roles: [
        { label: 'Docente', value: 'Docente' },
        { label: 'Administrador', value: 'Administrador' }
      ],
      columns: [
        { name: 'actions', label: 'Acciones', align: 'center' },
        { name: 'nombre', label: 'Nombre', field: 'nombre', align: 'left' },
        { name: 'ci', label: 'CI', field: 'ci', align: 'left' },
        { name: 'email', label: 'Email', field: 'email', align: 'left' },
        { name: 'telefono', label: 'Teléfono', field: 'telefono', align: 'left' },
        { name: 'usuario', label: 'Usuario', field: row => row.user?.username || '-', align: 'left' },
        { name: 'rol', label: 'Rol', field: row => row.user?.role || '-', align: 'left' },
      ],
      _debounceId: null
    }
  },

  mounted () {
    this.obtenerDocentes()
  },

  methods: {
    debouncedFetch () {
      clearTimeout(this._debounceId)
      this._debounceId = setTimeout(() => this.obtenerDocentes(), 350)
    },

    obtenerDocentes () {
      this.loading = true
      this.$axios.get('docentes', { params: { search: this.filter || undefined } })
        .then(res => { this.docentes = res.data })
        .catch(err => this.$alert.error(err.response?.data?.message || 'Error al obtener docentes'))
        .finally(() => { this.loading = false })
    },

    nuevoDocente () {
      this.docente = {}
      this.crearUsuario = false
      this.usuario = { username: '', email: '', password: '', role: 'Docente' }
      this.dialog = true
    },

    editarDocente (docente) {
      this.docente = { ...docente }
      // Si ya tiene user, prellenar y activar toggle (opcional)
      if (docente.user) {
        this.crearUsuario = true
        this.usuario = {
          username: docente.user.username || '',
          email: docente.user.email || docente.email || '',
          password: '',
          role: docente.user.role || 'Docente'
        }
      } else {
        this.crearUsuario = false
        this.usuario = { username: '', email: this.docente.email || '', password: '', role: 'Docente' }
      }
      this.dialog = true
    },

    autocompletarUsername () {
      const base = (this.docente.ci || this.docente.email || this.docente.nombre || '').toString().trim()
      this.usuario.username = base.replace(/\s+/g, '.').replace(/@.*$/, '').toLowerCase()
    },

    generarPassword () {
      const rnd = Math.random().toString(36).slice(-8)
      this.usuario.password = `Doc-${rnd}`
    },

    guardarDocente () {
      this.loading = true
      const payload = {
        ...this.docente,
        crear_usuario: this.crearUsuario,
        usuario: this.crearUsuario ? this.usuario : undefined
      }

      const req = this.docente.id
        ? this.$axios.put(`docentes/${this.docente.id}`, payload)
        : this.$axios.post('docentes', payload)

      req.then(() => {
        this.$alert.success('Docente guardado')
        this.dialog = false
        this.obtenerDocentes()
      }).catch(err => {
        this.$alert.error(err.response?.data?.message || 'Error al guardar')
      }).finally(() => { this.loading = false })
    },

    eliminarDocente (id) {
      this.$alert.dialog('¿Desea eliminar este docente?').onOk(() => {
        this.loading = true
        this.$axios.delete(`docentes/${id}`)
          .then(() => { this.$alert.success('Docente eliminado'); this.obtenerDocentes() })
          .catch(err => this.$alert.error(err.response?.data?.message || 'Error al eliminar'))
          .finally(() => { this.loading = false })
      })
    },

    resetPassword (row) {
      this.$alert.dialog('Se generará una nueva contraseña para este usuario. ¿Continuar?').onOk(() => {
        this.loading = true
        this.$axios.post(`docentes/${row.id}/reset-password`)
          .then(res => {
            const pass = res.data?.password
            this.$alert.success(`Nueva contraseña: ${pass}`)
          })
          .catch(err => this.$alert.error(err.response?.data?.message || 'No se pudo resetear la contraseña'))
          .finally(() => { this.loading = false })
      })
    }
  }
}
</script>
