<template>
    <div>

        <div class="card-body">

          <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">Введите ФИО</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" v-model="name" name="name" required autocomplete="name" autofocus>
                  <span class="invalid-feedback" role="alert">
                    <strong>5555</strong>
                  </span>
                </div>
         </div>
         <div class="form-group row">
             <label for="submit" class="col-md-4 col-form-label text-md-right"></label>

             <div class="col-md-6">
              <button class="btn btn-primary" @click="submit">Войти</button>

               </div>
        </div>

{{ this.user }}

        </div>



    </div>
    </template>

<script>
    export default {
        name: "RegUser",
        props:['user'],

        data() {
          return {
            name: ''
          };
        },
        mounted() {

      },
      methods: {

        submit() {
          axios.post('/editname',{ body: this.name })
          .then(response => {
              console.log(response.data);
          });
        }
      }
    }

</script>

<style scoped>
</style>
