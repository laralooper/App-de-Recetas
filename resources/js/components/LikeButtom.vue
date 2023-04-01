<template>
    <div>
        <span class="like-btn" @click="likeReceta" :class="{ 'like-active': isActive }"></span>
        <p>{{ cantidadLikes }} Les gusto esta receta</p>
    </div>
</template>

<script>
import { is } from '@babel/types';
import axios from 'axios';

export default {
    props: ['recetaId', 'like', 'likes'],
    data: function () {
        return {
            isActive: this.like,
            totalLikes: this.likes,
        }
    },
    methods: {
        likeReceta() {
            axios.post('/recetas/' + this.recetaId)
                .then(response => {
                    if (response.data.attached.length > 0) {    //valor attached tomado de la respuesta en la consola console.log(response)
                        this.$swal({
                            position: 'top-center',
                            icon: 'success',
                            title: 'Receta guardada',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.$data.totalLikes++;
                    } else {
                        this.$data.totalLikes--;
                    }

                    this.isActive = !this.isActive;
                })
                .catch(error => {
                    if (error.response.status === 401) {
                        window.location = '/register';
                    }
                });
        }
    },
    computed: {
        cantidadLikes() {
            return this.totalLikes
        }
    }
}
</script>