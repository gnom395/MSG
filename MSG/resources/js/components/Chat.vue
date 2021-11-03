<template>
    <div>
        <h1>отправка</h1>

        <textarea class="form-control" rows="10">{{ this.message.join('\n') }}</textarea>

        <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage" @checked="action">
        <span v-if="isActive">{{ isActive.name }} набирает текст</span>

    </div>
    </template>

<script>
    export default {
        name: "Chat",

        data() {
          return {
            message: [],
            textMessage: '',
            isActive: false
          };
        },
        mounted() {

        window.Echo.channel('chat')
          .listen('Message', ({message}) => {
            this.message.push(message)
            //console.log(message)
       })
       .listenForWhisper('typing', (e) => {
         this.isActive = e;
       });

       setTimeout(() => {
         this.isActive = false;
       }, 2000);
      },
      methods: {
        sendMessage(){
          axios.post('/messages',{ body: this.textMessage })
          this.message.push(this.textMessage)
          this.textMessage = ''
        },
        action(){
            window.Echo.channel('chat')
              .whisper('typing',{
                name: 'Вася'
              })
        }
      }
    }

</script>

<style scoped>
</style>
