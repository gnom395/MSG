<template>
<div>

    <div class="chatwin">

      <div class="scroll" ref="cha">

            <div v-for="messdat in chattextin" class="currency">

              <div v-if="messdat.success"> <br><br>
                <b-alert show variant="info">{{messdat.success}} &#128532;</b-alert>
              </div>


              <div v-else>
              <div style="text-align: right" v-if="messdat.from === myid">
              <div class="sendmes">
                <div class="row">
                  <div class="col" style="text-align: left">{{ messdat.message }}<br>
                    <div v-if="messdat.attach != 0"><b-button  @click="showModalFile(messdat.attach)">Вложенные файлы</b-button></div>
                  </div>
                  <div class="w-100"></div>
                  <div class="col text-right">
                    <small> {{ messdat.datesend }} </small>
                    <img v-if="messdat.read === 1" src="/assets/img/read1.png" title="Прочитано">
                    <img v-else-if="messdat.read === 2" src="/assets/img/waiting.png" title="Доставляется">
                    <img v-else src="/assets/img/read2.png" title="Не прочитано">
                  </div>
                </div>
              </div>
              </div>

              <div class="readmes" v-else>
                <div class="row">
                  <div class="col">{{ messdat.message }}<br></div>
                  <div class="w-100"></div>
                  <div class="col text-right">
                    <small> {{ messdat.datesend }} </small>
                  </div>
                </div>
              </div>
            </div>

           </div>


         </div>

<span v-if="notify">{{ this.notify }}</span>
<span v-if="isActive">{{ isActive.name }} набирает сообщение...</span>
<br>
</div>

</div>


</template>

<script>

  export default {

    data() {
      return {
        channelall: null,
        oldchannel: null,
        messPush: null,
        isActive: false,
        notify: null,
        myid: parseInt(this.myinfo.id)
    }},

    props: ['chattextin','myinfo'],

  //  computed:{
  //    channel(){
  //      return window.Echo.join('room.' + this.myinfo.id)
  //    }
  //  },
    methods : {


      webchatconn(userid) {

        if(userid < this.myinfo.id) {
          this.channelall = userid +'.'+ this.myinfo.id
          //this.$eventBus.$emit('roomIdToSubmit',this.channelall)
          //this.$parent.$options.methods.chid(this.channelall)
          // отправляем id канала на FormSubmit
          //this.$root.$emit('idchannel',this.channelall)

        } else {
          this.channelall = this.myinfo.id + '.' + userid
          //this.$eventBus.$emit('roomIdToSubmit',this.channelall)
          //this.$parent.$options.methods.chid(this.channelall)
          // отправляем id канала на FormSubmit
          //this.$root.$emit('idchannel',this.channelall)
        }

        //console.log(this.channelall);

        ////////////// отключаемся
        // если есть открытые каналы закрываем
        if(this.oldchannel !== null) {
          //window.Echo.leaveChannel('room.' + this.oldchannel)
          window.Echo.leave('room.' + this.oldchannel);
          console.log(this.oldchannel);
        }
        this.oldchannel = this.channelall


        window.Echo.join('room.' + this.channelall)
      //  this.channel
            .here((users) => {
                //  console.log(users);
            })
            /// пользователь зашел в чат
            .joining((user) => {

              this.notify = user.name + ' зашел в чат'
                setTimeout(() => {
                  this.notify = ''
                },4000);

              //console.log(user.name + ' ' + user.id + ' в сети');
            })        /// пользователь вышел из чата
            .leaving((user) => {

              this.notify = user.name + ' вышел из чата'
                setTimeout(() => {
                  this.notify = ''
                },4000);

              //console.log(user.name + ' не в сети');
            })
            .error((error) => {
              console.error(error);
            })
            .listen('PresenceChat', ({data}) => {
//[ { "id": 1498, "to": 12, "from": 13, "message": "454", "attach": "0", "read": 0, "datesend": "09:00 10.11.2021" },
//    { "message": "555", "to": "12", "ug": "user", "attach": null, "room_id": "12.13" } ]

              this.messPush
              this.chattextin.push(data)

              //this.scrollToDown()
              setTimeout(() => this.scrollToDown(), 200);
              //this.$eventBus.$emit('dataToChat',data)
              //console.log(data)
            });

            /////////// слушаем сокет кто печатает
            window.Echo.join('room.' + this.oldchannel)
            .listenForWhisper('typing', (e) => {
              this.isActive = e;

              if(this.tTimer) clearTimeout(this.tTimer)

              this.tTimer = setTimeout(() => {
                this.isActive = false
                //this.$root.$emit('isActive', false)
              },2000);
              //console.log(e)
            });



      },
      scrollToDown() {
        //alert('ddd')
        const objDiv = this.$refs.cha
        objDiv.scrollTop = objDiv.scrollHeight
      },
      showModalFile(idfiles) {
        this.$root.$emit('bv::show::modal', 'modal-1', '#btnShow', idfiles)
        this.idfiles = idfiles
      },
    },

  mounted() {
    this.$root.$on('webchatconn', (userid) => {
      //alert('chatwin')
        this.webchatconn(userid);
    })
  }
}

</script>

<style scoped>

.scroll {
overflow-y: scroll;
height: 400px;
overflow-x: hidden;
width: 100%;
}

.chatwin {

height: 400px; /* Высота блока */
width: 100%;
position: relative;
text-align:left;
height: 100%;
}



.sendmes {
  max-width:450px;
  background-color: #cce5fe;
  //color: #ffffff;
  border-radius: 5px 5px 0 5px;

  //display: inline-block;
  margin: 4px;
  padding: 4px;
  /right: 0;
  //display: block; /* смещает только блоки */
  //display: table-cell;
  //margin-left: auto;
   font-size: 16px;
   position: relative;
   display: inline-block;

}
.readmes {
  max-width:450px;
  background-color: #e2e3e5;
  border-radius: 0 5px 5px 5px;
  border-radius: 5px;
  margin: 4px;
  padding: 4px;
  position: relative;
  display: inline-block;
  font-size: 16px;
}

</style>
