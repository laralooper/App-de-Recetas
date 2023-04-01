<template>
    <input type="submit" class="d-block mb-2 btn btn-danger w-100" value="Eliminar" @click="eliminarReceta">
</template>

<script>
import axios from 'axios';

export default {
    props: ['recetaId'],
    methods: {
        eliminarReceta() {
            this.$swal({
                title: '¿Deseas eliminar esta receta?',
                text: "Una vez eliminada, no se puede recuperar",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {

                    //solicitud front-end-back
                    axios.post(`/recetas/${this.recetaId}`)
                        .then(respuesta => {
                            this.$swal(
                                'Receta Eliminada!',
                                'Se eliminó la receta.',
                                'success'
                            )
                            //eliminar elemento eliminado del DOM
                            this.$el.parentNode.parentNode.parentNode.removeChild(this.$el.parentNode.parentNode);  //observa desde this.$el y empieza a buscar al padre a partir de this.$el y luego al hijo a partir de this.$el. this.$el es el input.
                        })
                        .catch(error => console.log(error));


                }
            })
        }
    }

}
</script>