<template>
  <div>

    <b-modal id="modal-1" title="Вложения">

      <div v-for="file in listFileAtta">
        <a href="" target="_blank">{{file.filename}}</a>
      </div>

    </b-modal>

  </div>

</template>

<script>
  export default {
    name: "WinFiles",
    data() {
      return {
        listFileAtta: []
      }},


  //  props: ['idfiles'],

    methods: {
      //if(this.idfiles) {
      loadListFiles(idfiles) {
        console.log(idfiles);

        axios.post('/getnamefiles', { idfiles: idfiles })
          .then(response => {
            this.listFileAtta = response.data;
            //console.log(response.data);
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
