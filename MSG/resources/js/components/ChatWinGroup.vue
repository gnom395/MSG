<template>
<div>

    <div class="chatwin">

      <div class="scroll" ref="cha">


            <div v-for="messdat in chattextin" class="currency">

              <div v-if="messdat.success === 0">
                <b-alert show variant="info">Нет сообщений &#128532;</b-alert>
              </div>


              <div style="text-align: right" v-if="messdat.from === myid">
              <div class="sendmes">
                <div class="row">
                  <div class="col" style="text-align: left">{{ messdat.message }}<br>

                    <div v-if="messdat.attach > 0">
                      <i class="bi bi-paperclip fileAttach" @click="showModalFile(messdat.attach)"></i>
                    </div>

                  </div>
                  <div class="w-100"></div>
                  <div class="col text-right">
                    <small id="dropdownMenu" data-toggle="dropdown" style="cursor:pointer"> {{ messdat.datesend }} </small>
                    <img v-if="messdat.read === 1" src="/assets/img/read1.png" title="Прочитано">
                    <img v-else-if="messdat.read === 2" src="/assets/img/waiting.png" title="Доставляется">
                    <img v-else src="/assets/img/read2.png" title="Не прочитано">
                      <div class="dropdown-menu" aria-labelledby="dropdownMenu">
                        <button class="dropdown-item" type="button" @click="delMsg(messdat.id, 0)">Удалить</button>
                        <button v-if="myinfo.role === 1" class="dropdown-item" type="button" @click="delMsg(messdat.id, 1)">Удалить у всех</button>
                      </div>
                  </div>
                </div>
               </div>
              </div>
              <div class="readmes" v-else>
                <div class="row">
                  <div class="col">{{ messdat.message }}<br>
                    <div v-if="messdat.attach > 0">
                      <i class="bi bi-paperclip fileAttach" @click="showModalFile(messdat.attach)"></i>
                    </div>
                  </div>
                  <div class="w-100"></div>
                  <div class="col text-right">
                    <small id="dropdownMenu" data-toggle="dropdown" style="cursor:pointer"> {{ messdat.datesend }} </small>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu">
                      <button class="dropdown-item" type="button" @click="delMsg(messdat.id, 0)">Удалить</button>
                      <button v-if="myinfo.role === 1" class="dropdown-item" type="button" @click="delMsg(messdat.id, 1)">Удалить у всех</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<UploadFile></UploadFile>
         </div>



<br>
</div>



<WinFiles ref="loadListFiles"></WinFiles>
</div>

</template>

<script>
  import UploadFile from '../components/UploadFile';
  import WinFiles from '../components/WinFiles';

  export default {
    components: {
        UploadFile,
        WinFiles
    },

    data() {
      return {
        oldchannel: null,
        idfiles: null,
        isActive: false,
        myid: parseInt(this.myinfo.id),
        idmes: null,
        delall: 0,
        userOnine: 0,
        UserPostOnline: false,
        UserChannel: null,
        NewMes: null,
        chantype: null
    }},

    props: ['chattextin','myinfo'],


  //  computed:{
  //    channel(){
  //      return window.Echo.join('room.' + this.myinfo.id)
  //    }
  //  },
    methods : {

      delMsg(idmes,delall) {

              console.log(idmes + ' ' +delall)
        if(idmes) {
          this.$bvModal.msgBoxConfirm('Вы уверены, что хотите удалить сообщение?')
          .then(value => {
            //console.log(value);
            if(value === true) {
              axios.post('/delmes', { idmes: idmes, delall: delall })
                .then(response => {
                  //  console.log(response.data)

                    /// удалим сообщение
                    for (var p = 0; p < this.chattextin.length; p++){
                      if (idmes == this.chattextin[p].id ){
                        this.chattextin[p].message = 'Удалено';
                        this.chattextin[p].datesend = '';
                        //console.log(this.usertext[i].online);
                        break;
                      }
                    }

                  if(response.data === 1){
                    console.log('Сообщение удалено')
                  } else {
                    this.$bvModal.msgBoxOk('Поздравляю! Вы все сломали. Сообщите об ошибке в осудт')
                  }
                  //this.message.push(response.message)
                })
            }
          })


              //console.log(response);
       }


          //.catch(err => {
          //  // An error occurred
          //})
      },
      webchatconn(userid) {

        this.createIdChannel();

        this.UserPostOnlinefun(false);

        ////////////// отключаемся
        // если есть открытые каналы закрываем
        if(this.oldchannel !== null) {
          //window.Echo.leaveChannel('room.' + this.oldchannel)
          window.Echo.leave('room.' + this.oldchannel);
          //console.log(this.oldchannel);
        }
        this.oldchannel = this.UserChannel

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        window.Echo.join('room.' + this.UserChannel)
      //  this.channel
            .here((users) => {
                //  console.log(users);

                  for (var j = 0; j < users.length; j++){
                    if (this.$route.params.id == users[j].id ){
                      console.log(this.$route.params.id +' '+users[j].id);
                      this.UserPostOnlinefun(true);
                        break;
                    }
                  }
            })
            /// пользователь зашел в чат
            .joining((user) => {

              this.UserPostOnlinefun(true);

              //this.makeToast('Оповещение',user.name + ' зашел в чат');
              //this.notify = user.name + ' зашел в чат'
              //  setTimeout(() => {
              //    this.notify = ''
              //  },4000);

              //console.log(user.name + ' ' + user.id + ' в сети');
            })        /// пользователь вышел из чата
            .leaving((user) => {

              this.UserPostOnlinefun(false);

              //this.makeToast('Оповещение',user.name + ' вышел из чата');
              //this.notify = user.name + ' вышел из чата'
              //  setTimeout(() => {
              //    this.notify = ''
              //  },4000);

              //console.log(user.name + ' не в сети');
            })
            .error((error) => {
              console.error(error);
            })
            .listen('PresenceChat', ({data}) => {

              if(this.chattextin[0].success == 0) {
                this.chattextin[0].success = '';
              }
              //console.log('ggg '+this.chattextin[0].success);
              //this.messPush
              this.chattextin.push(data);

              this.NewMes = data.id;
              this.NewMesAnswer(this.NewMes);



              //this.scrollToDown()
              setTimeout(() => this.scrollToDown(), 200);
              //this.$eventBus.$emit('dataToChat',data)
              //console.log(data)
            });

            /////////// слушаем сокет кто печатает
            window.Echo.join('room.' + this.UserChannel)
            .listenForWhisper('typing', (e) => {

              if(e.name) {
                //this.isActive = e;
                this.$root.$emit('PrintMess', true);

                if(this.tTimer) clearTimeout(this.tTimer)

                this.tTimer = setTimeout(() => {
                //  this.isActive = false
                this.$root.$emit('PrintMess', false);
                  //this.$root.$emit('isActive', false)
                },2000);
              }
              if(e.readmes) {
                ///  сообщение прочитано
                for (var z = 0; z < this.chattextin.length; z++){
                  if (e.readmes == this.chattextin[z].id ){
                    this.chattextin[z].read = 1;
                    break;
                  }
                }
              }
              //console.log(e.readmes)
            });



      },
      makeToast(title,text) {
        this.toastCount++
        this.$bvToast.toast(text, {
          title: title,
          autoHideDelay: 5000,
          appendToast: false
        })
      },
      NewMesAnswer(NewMes){

           window.Echo.join('room.' + this.UserChannel)
            .whisper('typing',{
              readmes: NewMes
            });
        //console.log(this.NewMes);

      },
      scrollToDown() {
        //alert('ddd')
        const objDiv = this.$refs.cha
        objDiv.scrollTop = objDiv.scrollHeight
      },
      showModalFile(idfiles) {
        this.$root.$emit('bv::show::modal', 'modal-1', '#btnShow', idfiles)

        /// вызываем функцию из дочернего компонента
        this.$refs.loadListFiles.loadListFiles(idfiles);
        //this.idfiles = idfiles
      },
      delMes(idmes) {
        this.$root.$emit('bv::show::modal', 'modal-2', '#btnShow', idmes)
        this.idmes = idmes
      },
      createIdChannel(){
        /// делаем id канала

        if(this.$route.params.id < this.myinfo.id) {
          this.UserChannel = this.$route.params.id +'.'+ this.myinfo.id
        } else {
          this.UserChannel = this.myinfo.id + '.' + this.$route.params.id
        }
        //  console.log(this.UserChannel);
      },
      UserPostOnlinefun(tf){
        this.UserPostOnline = tf;
        //alert('111');
        this.$root.$emit('UserPostOnline',tf)

      }
    },

  mounted() {

    this.createIdChannel();

    /// если зашли по url
    if(typeof(this.$route.params.id) !== 'undefined' ) {
        this.webchatconn(this.$route.params.id);
    }

    this.$root.$on('webchatconn', (userid) => {
      //alert('chatwin')
        this.webchatconn(userid);
    })
    //this.$root.$on('OnlineUser', (userid,username,useronine) => {
    //    alert('userid');
        //this.userOnine = userid;
    //})
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
.fileAttach {
  font-size: 30px;
  cursor: pointer;
}
.fileAttach:active {
  opacity: .8;
}
.fileAttach:Hover {
  opacity: .4;
}

</style>
