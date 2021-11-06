<template>
    <div>
        <h1>отправка</h1>
{{ this.dat }}

        <textarea class="form-control" rows="10">{{ this.message.join('\n') }}</textarea>

        <input type="text" class="form-control" name="textMessage" v-model="textMessage" @keyup.enter="sendMessage" @keydown="action">
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
            isActive: false,
            dat: ''
          };
        },
        computed:{
          channel(){
            return window.Echo.channel('chat')
          }
        },
        mounted() {

        this.channel
          .listen('Message', ({message}) => {
            this.message.push(message)
            console.log(message)
       });
        //this.channel
      /// .listenForWhisper('typing', (e) => {
         this.isActive = e;
    //   });

       //setTimeout(() => {
      //   this.isActive = false;
      // }, 2000);
      },
      methods: {
        sendMessage(){

        //  axios.get('/messages?body=' + this.textMessage)
          //  .then(response => (
            //  this.chattext = response.data
            //  console.log('aaa')
              //this.oldChat(response.data[0].id)
            //))

          axios.post('/messages',{ body: this.textMessage })
          .then(response => {

            console.log(response.data);


            });
          this.message.push(this.textMessage)
          this.textMessage = ''
        },
        action(){
            this.channel
              .whisper('typing',{
                name: 'Вася'
              })
        }
      }
    }

</script>

<style scoped>
</style>
