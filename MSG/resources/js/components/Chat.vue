<template>
    <div>
        <h1>отправка</h1>
{{ this.dat }}

        <textarea class="form-control" rows="10">{{ this.message.join('\n') }}</textarea>

        <input type="text" class="form-control" name="textMessage" v-model="textMessage" @keyup.enter="sendMessage" @keydown="action">
        <span v-if="isActive"> набирает текст</span>


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
            dat: '',
            name: 'Vasy',
            room_id: 1,
            tTimer: false
          };
        },
        computed:{
          channel(){
            return window.Echo.private('room.' + this.room_id)
          }
        },
        mounted() {

        this.channel
          .listen('PrivateChat', ({data}) => {
            this.message.push(data.body)
            console.log(data)
       });
       this.channel
       .listenForWhisper('typing', (e) => {
         this.isActive = e;

         if(this.tTimer) clearTimeout(this.tTimer)

         this.tTimer = setTimeout(() => {
           this.isActive = false
         },2000);
         //console.log(e)
       });

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

          axios.post('/messages',{ body: this.textMessage, user: 1, room_id: this.room_id })
          .then(response => {

            //console.log(response.data);


            });
          this.message.push(this.textMessage)
          this.textMessage = ''
        },
        action(){
          this.channel
              .whisper('typing',{
                name: this.name
              })
        }
      }
    }

</script>

<style scoped>
</style>
