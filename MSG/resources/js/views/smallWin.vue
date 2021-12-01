<template>


  <div>
{{this.myinfo}}
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
    //  seen: true,
      };
    },
    methods : {
      getMesState(){
        axios
        .get('/getsmallwin')
        .then(response => (
          console.log(response.data)
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
