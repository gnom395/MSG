<template>


  <div>

    <b-alert v-if="this.CountMes === 0" show variant="secondary">Нет сообщений</b-alert>
    <b-alert v-else show variant="success">Есть новые сообщения ({{this.CountMes}})</b-alert>

  </div>


</template>

<script>
//import axios from 'axios';
//import moment from 'moment'
//import UsersWin from '../components/UsersWin'

  export default {

    props: ['myinfo'],

    data() {
      return {
        CountMes: null,
      };
    },
    methods : {
      getMesState(){
        axios
        .get('/getsmallwin')
        .then(response => (
          this.CountMes = response.data
        ))
        .catch(error => console.log(error));

      },
    },
    mounted() {

      this.getMesState();
    }

  }
</script>

<style scoped>


</style>
