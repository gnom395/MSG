<template>
<div>

    <div class="chatwin">

      <div class="scroll" ref="cha">

            <div v-for="messdat in chattextin" class="currency">


              <div class="container sendmes" v-if="messdat.mymes === 1">
                <div class="row">
                  <div class="col">{{ messdat.message }}<br>

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

              <div class="container readmes" v-else>
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


<br>


</div>
id канала: {{ this.channelall }}
</div>


</template>

<script>

  export default {

    data() {
      return {
        channelall: null,
        oldchannel: null
    }},

    props: ['chattextin','usermy'],

  //  computed:{
  //    channel(){
  //      return window.Echo.join('room.' + this.usermy.id)
  //    }
  //  },
    methods : {


      webchatconn(userid) {

        if(userid < this.usermy.id) {
          this.channelall = userid +'.'+ this.usermy.id
          //this.$eventBus.$emit('roomIdToSubmit',this.channelall)
          /// запускаем на родителе
          //this.$parent.$options.methods.chid(this.channelall)

        } else {
          this.channelall = this.usermy.id + '.' + userid
          //this.$eventBus.$emit('roomIdToSubmit',this.channelall)
          /// запускаем на родителе
          //this.$parent.$options.methods.chid(this.channelall)
        }

        //console.log(this.channelall);

        // если есть открытые каналы закрываем
        if(this.oldchannel !== null) {
          window.Echo.leave('room.' + this.oldchannel)
          //alert(this.oldchannel)
        }

        this.oldchannel = this.channelall


        window.Echo.join('room.' + this.channelall)
      //  this.channel
            .here((users) => {
                //  console.log(users);
            })
            /// пользователь зашел в чат
            .joining((user) => {
              console.log(user.name + ' ' + user.id + ' в сети');
            })        /// пользователь вышел из чата
            .leaving((user) => {

              console.log(user.name + ' не в сети');
            })
            .error((error) => {
              console.error(error);
            })
            .listen('PresenceChat', ({data}) => {
              this.chattextin.push(data)


              setTimeout(() => this.scrollToDown(), 1000);
              //this.$eventBus.$emit('dataToChat',data)
              console.log(data)
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
    /// слушаем из userwin
        this.$root.$on('webchatconn', (userid) => {
            this.webchatconn(userid)
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
  max-width:300px;
  min-width: 200px;
  background-color: #3490dc;
  color: #ffffff;
  border-radius: 5px 5px 0 5px;
  right: 0;
  //display: inline-block;
  margin: 3px;
  padding: 3px;

  display: block; /* смещает только блоки */
  margin-left: auto;
   font-size: 16px;

}
.readmes {
  max-width: 300px;
  min-width: 200px;
  background-color: #ffffff;
  border-radius: 0 5px 5px 5px;
  border-radius: 5px;
  margin: 3px;
  padding: 3px;
  position: relative;
  display: inline-block;
  font-size: 16px;
}

</style>
