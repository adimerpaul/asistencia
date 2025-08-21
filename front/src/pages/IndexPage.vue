<template>
  <q-page class="q-pa-md">
    <!-- Encabezado + filtros -->
    <div class="row items-center q-mb-md">
      <div class="col">
        <div class="text-h5 text-bold">Dashboard</div>
        <div class="text-caption text-grey-7">Resumen general</div>
      </div>
      <div class="col-auto">
        <div class="row q-col-gutter-sm">
          <div class="col-6 col-md-4">
            <q-select
              v-model="gestionSel"
              :options="gestiones"
              label="Gestión"
              dense outlined clearable
              @update:model-value="refrescarAsignaciones"
            />
          </div>
          <div class="col-6 col-md-4">
            <q-select
              v-model="cursoSel"
              :options="cursos"
              option-label="nombre"
              option-value="id"
              label="Curso"
              emit-value map-options
              dense outlined clearable
              @update:model-value="refrescarAsignaciones"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Métricas -->
    <div class="row q-col-gutter-md q-mb-md">
      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="metric-card">
          <q-card-section class="row items-center">
            <q-avatar icon="school" color="primary" text-color="white" />
            <div class="col q-pl-md">
              <div class="text-caption text-grey-7">Cursos</div>
              <div class="text-h6">{{ kpi.cursos }}</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="metric-card">
          <q-card-section class="row items-center">
            <q-avatar icon="person" color="teal" text-color="white" />
            <div class="col q-pl-md">
              <div class="text-caption text-grey-7">Docentes</div>
              <div class="text-h6">{{ kpi.docentes }}</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="metric-card">
          <q-card-section class="row items-center">
            <q-avatar icon="groups" color="deep-orange" text-color="white" />
            <div class="col q-pl-md">
              <div class="text-caption text-grey-7">Estudiantes</div>
              <div class="text-h6">{{ kpi.estudiantes }}</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <q-card flat bordered class="metric-card">
          <q-card-section class="row items-center">
            <q-avatar icon="dashboard" color="purple" text-color="white" />
            <div class="col q-pl-md">
              <div class="text-caption text-grey-7">Asignaciones</div>
              <div class="text-h6">{{ kpi.asignaciones }}</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="row q-col-gutter-md">
      <!-- Barras: Asignaciones por Curso -->
      <div class="col-12 col-md-6">
        <q-card flat bordered>
          <q-card-section class="text-subtitle1">Asignaciones por curso</q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart type="bar" height="320" :options="chartCurso.options" :series="chartCurso.series" />
          </q-card-section>
        </q-card>
      </div>

      <!-- Dona: Distribución por tipo de curso -->
      <div class="col-12 col-md-6">
        <q-card flat bordered>
          <q-card-section class="text-subtitle1">Distribución por tipo de curso</q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart type="donut" height="320" :options="chartTipos.options" :series="chartTipos.series" />
          </q-card-section>
        </q-card>
      </div>

      <!-- Barras: Asignaciones por Gestión -->
      <div class="col-12 col-md-6">
        <q-card flat bordered>
          <q-card-section class="text-subtitle1">Asignaciones por gestión</q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart type="bar" height="320" :options="chartGestion.options" :series="chartGestion.series" />
          </q-card-section>
        </q-card>
      </div>

      <!-- Horizontal: Docentes con más asignaciones -->
      <div class="col-12 col-md-6">
        <q-card flat bordered>
          <q-card-section class="text-subtitle1">Top docentes por asignaciones</q-card-section>
          <q-separator />
          <q-card-section>
            <apexchart type="bar" height="320" :options="chartDocentes.options" :series="chartDocentes.series" />
          </q-card-section>
        </q-card>
      </div>
    </div>

    <q-inner-loading :showing="loading">
      <q-spinner-dots size="32px" />
    </q-inner-loading>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import VueApexCharts from 'vue3-apexcharts'

export default defineComponent({
  name: 'IndexPage',
  components: { apexchart: VueApexCharts },
  data () {
    return {
      loading: false,

      // filtros
      gestiones: [],
      gestionSel: null,
      cursoSel: null,

      // catálogos
      cursos: [],
      docentes: [],

      // data
      asignaciones: [],
      estudiantes: [],

      // KPIs
      kpi: {
        cursos: 0,
        docentes: 0,
        estudiantes: 0,
        asignaciones: 0
      },

      // charts models
      chartCurso:   { options: {}, series: [] },
      chartTipos:   { options: {}, series: [] },
      chartGestion: { options: {}, series: [] },
      chartDocentes:{ options: {}, series: [] }
    }
  },
  async mounted () {
    // gestiones: año-3 .. año+3
    const y = new Date().getFullYear()
    this.gestiones = Array.from({ length: 7 }, (_, i) => String(y - 3 + i))

    await this.cargarBase()
    this.armarGraficos()
  },
  methods: {
    async cargarBase () {
      this.loading = true
      try {
        const [cursosRes, docentesRes, estudiantesRes] = await Promise.all([
          this.$axios.get('cursos'),
          this.$axios.get('docentes'),
          this.$axios.get('estudiantes')
        ])
        this.cursos      = cursosRes.data || []
        this.docentes    = docentesRes.data || []
        this.estudiantes = estudiantesRes.data || []

        // asignaciones (con filtros si hay)
        await this.refrescarAsignaciones()

        // KPIs
        this.kpi = {
          cursos: this.cursos.length,
          docentes: this.docentes.length,
          estudiantes: this.estudiantes.length,
          asignaciones: this.asignaciones.length
        }
      } catch (e) {
        this.$alert?.error?.('No se pudo cargar el dashboard')
      } finally {
        this.loading = false
      }
    },

    async refrescarAsignaciones () {
      // vuelve a pedir asignaciones con filtros
      const params = {
        gestion: this.gestionSel || undefined,
        curso_id: this.cursoSel || undefined,
        with_estudiantes: false
      }
      const { data } = await this.$axios.get('asignaciones', { params })
      this.asignaciones = Array.isArray(data) ? data : (data?.data || [])
      // actualizar KPI y charts dependientes
      this.kpi.asignaciones = this.asignaciones.length
      this.armarGraficos()
    },

    armarGraficos () {
      this.armarAsignacionesPorCurso()
      this.armarDistribucionTipos()
      this.armarAsignacionesPorGestion()
      this.armarTopDocentes()
    },

    // ---- helpers de agrupación
    groupCount (arr, keyGetter) {
      const map = new Map()
      arr.forEach(it => {
        const key = keyGetter(it)
        if (!key) return
        map.set(key, (map.get(key) || 0) + 1)
      })
      return map
    },

    // ---- chart 1: barras por curso
    armarAsignacionesPorCurso () {
      const map = this.groupCount(this.asignaciones, a => a.curso?.nombre || a.curso_name || null)
      const labels = Array.from(map.keys())
      const data   = Array.from(map.values())

      this.chartCurso = {
        series: [{ name: 'Asignaciones', data }],
        options: {
          chart: { toolbar: { show: false } },
          xaxis: { categories: labels, labels: { rotate: -30, trim: true } },
          yaxis: { labels: { formatter: v => String(v) } },
          dataLabels: { enabled: false },
          tooltip: { y: { formatter: v => `${v}` } }
        }
      }
    },

    // ---- chart 2: dona tipos de curso (de catálogo)
    armarDistribucionTipos () {
      const map = this.groupCount(this.cursos, c => c.tipo || 'Otros')
      const labels = Array.from(map.keys())
      const series = Array.from(map.values())

      this.chartTipos = {
        series,
        options: {
          labels,
          legend: { position: 'bottom' },
          dataLabels: { enabled: true },
          plotOptions: { pie: { donut: { size: '65%' } } }
        }
      }
    },

    // ---- chart 3: barras por gestión (usa listado actual)
    armarAsignacionesPorGestion () {
      const map = this.groupCount(this.asignaciones, a => String(a.gestion || '—'))
      const labels = Array.from(map.keys()).sort()
      const data   = labels.map(k => map.get(k))

      this.chartGestion = {
        series: [{ name: 'Asignaciones', data }],
        options: {
          chart: { toolbar: { show: false } },
          xaxis: { categories: labels },
          dataLabels: { enabled: false }
        }
      }
    },

    // ---- chart 4: top docentes (horizontal)
    armarTopDocentes () {
      const map = this.groupCount(this.asignaciones, a => a.docente?.nombre || 'Sin docente')
      // ordenar desc y tomar top 10
      const arr = Array.from(map.entries()).sort((a,b) => b[1]-a[1]).slice(0, 10)
      const labels = arr.map(([k]) => k)
      const data   = arr.map(([,v]) => v)

      this.chartDocentes = {
        series: [{ name: 'Asignaciones', data }],
        options: {
          chart: { toolbar: { show: false } },
          plotOptions: { bar: { horizontal: true } },
          xaxis: { categories: labels },
          dataLabels: { enabled: false }
        }
      }
    }
  }
})
</script>

<style scoped>
.metric-card { min-height: 88px; }
</style>
