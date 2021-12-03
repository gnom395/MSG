<template>
  <div>

    <b-modal id="modal-1" title="Вложения">

      <div v-for="file in listFileAtta">
        <b><a :href="file.url" target="_blank">{{file.filename}}</a></b>
        <i v-if="file.mime == 'image/jpeg'" class="bi bi-images"></i>
      </div>
      <div class="text-center">
        <b-spinner v-if="loading" label="Loading..."></b-spinner>
      </div>
    </b-modal>


  </div>

</template>

<script>
  export default {
    name: "WinFiles",
    data() {
      return {
        listFileAtta: [],
        loading: false
      }},


  //  props: ['idfiles'],

    methods: {
      //if(this.idfiles) {

      loadListFiles(idfiles) {

        this.listFileAtta = '';
        //console.log(idfiles);
        this.loading = true;

        axios.post('/getnamefiles', { idfiles: idfiles })
          .then(response => {
            this.listFileAtta = response.data;
            //console.log(response.data);
            this.loading = false;
        })
        .catch(error => {
          console.log(error)
        });
      }
    }

  }
</script>

<style scoped>

</style>
