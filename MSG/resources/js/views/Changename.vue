<template>
    <div>



        <div class="card-body">


          <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">Введите ФИО</label>

              <div class="col-md-6">
                <input  type="text" class="form-control" v-model="name" required autocomplete="name" autofocus>
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

        <p v-if="errors.length">
          <b>Пожалуйста исправьте указанные ошибки:</b>
          <ul>
            <li v-for="error in errors">{{ error }}</li>
          </ul>
        </p>

        </div>



    </div>
    </template>

<script>
    export default {
        name: "ChangeName",

        data() {
          return {
            errors: [],
            name: null
          };
        },
        mounted() {

      },
      methods: {

        submit() {

          this.errors = [];

          if (!this.name) {
            this.errors.push('Укажите имя.');
          }

          if (!this.errors.length) {

            axios.post('/editname',{ name: this.name })
            .then(response => {
              if(response.data == 1){
                  window.location.href = "/"
              }

                console.log(response.data);
            });

          }


        }
      }
    }

</script>

<style scoped>
</style>
