<template>
    <div>



        <div class="card-body">

          <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">Имя Фамилия</label>

              <div class="col-md-6">
                <input  type="text" class="form-control" v-model="name" required autocomplete="name" autofocus>
                  <small class="form-text text-muted">введите ваше имя и фамилию</small>
              </div>
         </div>

         <div class="form-group row">
             <label for="name" class="col-md-4 col-form-label text-md-right">Почта</label>

             <div class="col-md-6">
               <input  type="text" class="form-control" v-model="email" required autocomplete="email">
                  <small class="form-text text-muted">адрес вашей корпоративной почты (не обязательно)</small>
               </div>
        </div>


         <div class="form-group row">
             <label for="submit" class="col-md-4 col-form-label text-md-right"></label>
             <div class="col-md-6">
              <button class="btn btn-primary" @click="submit">Сохранить и войти</button>
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
            name: null,
            email: null
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
