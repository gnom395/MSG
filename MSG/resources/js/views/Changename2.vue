<template>
    <div>
        <h1>отправка</h1>

        <textarea class="form-control" rows="10">{{ message.join('\n') }}</textarea>

        <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage">

    </div>
    </template>

<script>
    export default {
        name: "Changename",

        data() {
          return {
            message: [],
            textMessage: ''
          };
        },
        mounted() {
        window.Echo.private('chat')
          .listen('Message', ({message}) => {
            this.messages.push(message)
            console.log(message);
        });
      },
      methods: {
        sendMessage(){
          axios.post('/messages',{ body: this.textMessage })
          this.message.push(this.textMessage)
          this.textMessage = ''
        }
      }
    }

</script>

<style scoped>
</style>
