<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <div class="card-body">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Crear persona
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form @submit.prevent="guardar">
                                            <div class="form-group row">
                                                <label for="nombre"  class="col-sm-3 col-form-label">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.nombre" class="form-control" id="nombre" placeholder="Nombre">
                                                </div>
                                                <label for="apellidos" class="col-sm-3 col-form-label">Apellidos</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.apellidos" class="form-control" id="apellidos" placeholder="Apellidos">
                                                </div>
                                                <label for="ci" class="col-sm-3 col-form-label">CI</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.ci" class="form-control" id="ci" placeholder="CI">
                                                </div>
                                                <label for="sexo" class="col-sm-3 col-form-label">Sexo</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.sexo" class="form-control" id="sexo" placeholder="Sexo">
                                                </div>
                                                <label for="celular" class="col-sm-3 col-form-label">Celular</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.celular" class="form-control" id="celular" placeholder="Celular">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-trash"></i> Close</button>
                                                <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Guardar</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel2">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form @submit.prevent="update">
                                            <div class="form-group row">
                                                <label for="nombre"  class="col-sm-3 col-form-label">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.nombre" class="form-control" id="nombre" placeholder="Nombre">
                                                </div>
                                                <label for="apellidos" class="col-sm-3 col-form-label">Apellidos</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.apellidos" class="form-control" id="apellidos" placeholder="Apellidos">
                                                </div>
                                                <label for="ci" class="col-sm-3 col-form-label">CI</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.ci" class="form-control" id="ci" placeholder="CI">
                                                </div>
                                                <label for="sexo" class="col-sm-3 col-form-label">Sexo</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.sexo" class="form-control" id="sexo" placeholder="Sexo">
                                                </div>
                                                <label for="celular" class="col-sm-3 col-form-label">Celular</label>
                                                <div class="col-sm-9">
                                                    <input type="text" v-model="persona.celular" class="form-control" id="celular" placeholder="Celular">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-trash"></i> Close</button>
                                                <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Guardar</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Ci</th>
                                    <th>Sexo</th>
                                    <th>celular</th>
                                    <th>opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(i,index) in personas" :key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{i.nombre}}</td>
                                    <td>{{i.apellidos}}</td>
                                    <td>{{i.ci}}</td>
                                    <td>{{i.sexo}}</td>
                                    <td>{{i.celular}}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" @click="actualizar(i)"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-sm" @click="eliminar(i.id)"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <button id="boton">precioname</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const axios = require('axios');
    export default {
        data(){
            return {
                persona:{},
                personas:[],
            }
        },
        mounted() {
            console.log('Este es el componente de prueba')
            this.datos();
            $('#boton').click(function(){
                // alert('click');

                var doc = new jsPDF()

                doc.text('Hello world!', 10, 10)
                doc.save('a4.pdf')


            });
        },

        methods:{
            datos(){
                axios.get('/persona').then(res=>{
                    // console.log(res.data);
                    this.personas=res.data;
                    // console.log(this.personas);
                })
            },
            guardar(){
                // console.log(this.persona);
                axios.post('/persona',this.persona).then(res=>{
                    // console.log(res.data);
                    // this.personas=res.data;
                    // console.log(this.personas);
                    $('#exampleModal').modal('hide');
                    this.datos();
                })
            },
            eliminar(id){
                if(confirm('Seguro de eliminar?')){
                    // console.log('si');
                    axios.delete('/persona/'+id).then(res=>{
                        this.datos();
                    });
                }
                // else{
                //     console.log('no');
                // }
            },
            actualizar(i){
                this.persona=i;
                $('#modificar').modal('show');
            },
            update(){
                axios.put('/persona/'+this.persona.id,this.persona).then(res=>{
                    // console.log(res.data);
                    // this.personas=res.data;
                    // console.log(this.personas);
                    $('#modificar').modal('hide');
                    this.datos();
                })
            }

        }
    }
</script>
