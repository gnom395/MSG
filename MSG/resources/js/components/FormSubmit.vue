<template>

  <div class="butdown">

<form @submit.prevent="submit" enctype="multipart/form-data">


    <b-input-group>

      <b-input-group-prepend>
        <label>
          <img src="/assets/img/attachment.png" class="filebut"> <input type="file" style="display: none;" name="image" multiple="" @change="fileInputChange">
        </label>
      </b-input-group-prepend>

      <b-form-input class="inputsend" type="text" placeholder="Введите текст" v-model="message" rows="1" ref="message" v-on:keyup.ctrl.enter="submit"  autocomplete="off" @keydown="UserPrintMess"></b-form-input>


      <b-input-group-prepend>
        <!--<i class="bi bi-send sendbut" style="font-size:30px;"></i>-->
        <label>
          <input v-if="this.message" class="sendbut" type="image" src="/assets/img/send.png" border="0" alt="Submit">
          <input v-else-if="this.idBaseFile !== 0" class="sendbut" type="image" src="/assets/img/send.png" border="0" alt="Submit">
        </label>
      </b-input-group-prepend>


    </b-input-group>


      <input type="hidden" v-model="idBaseFile">


</form>


</div>
</template>

<script>
//  import axios from 'axios';
  //import ListFile from '../components/ListFile';
  import Spin from '../components/Spin';
//  import UploadFile from '../components/UploadFile';

    export default {
      components: {
      //  ListFile,
        Spin,
//        UploadFile
      },

      //props: ['myid','myip','myname'],
      props: ['myinfo'],

    data() {
      return {
        message: '',
        answer: '',
        id: '',
        dismissSecs: 7,
        file: '',
        usertext: null,
        listfile: [],
        filesend: '',
        attachfile: 0,
        attachfiletmp: '',
        room_id: null,
        channelid: null,
        UserPostOnline: null,
        filesFinish: [],
        idBaseFile: 0,
        chantype: null

      };
    },
  //  beforeDestroy() {
  //    this.$eventBus.$off('roomIdToSubmit');
    //},
    mounted() {

      //this.$root.$on('filesFinish', (filesFinish) => {

      //  console.log(filesFinish);
    //  })
    this.$root.$on('idBaseFile', (idBaseFile) => {
      this.idBaseFile = idBaseFile;
    })

      this.$root.$on('UserPostOnline', (online) => {
        if(online === true) {
          this.UserPostOnline = 1;
        }else{
          this.UserPostOnline = 0;
        }

        ///idBaseFile(data){
        //  alert(data);
        //},
        //alert(online);
          //this.webchatconn(userid);
      })

        },
   methods : {

     /// загрузка файлов
     fileInputChange(){
       /// chatwin
       this.$root.$emit('fileInputChange')
     },
     /// файлы
     //AttachFile(filesFinish){
    //   console.log(filesFinish);
     //},

     /// печатает сообщение
     UserPrintMess(){

          window.Echo.join('room.' + this.channelid)
           .whisper('typing',{
             name: this.myinfo.name
           })
     },


      //handleFileUpload(){
      //  this.file = this.$refs.file.files[0];
    //  },


      submit(){

        /// ставим фокус на инпуте
        this.$refs.message.focus()

        const today = new Date();
        const timenow = today.getHours() + ":" + today.getMinutes();

        // делаем channelid
          if(this.$route.params.ug == 'user') {
            this.chantype = 'user';
          } else {
            this.chantype = 'group';
          }
          if(this.$route.params.id < this.myinfo.id) {
            this.channelid = this.chantype + '.' + this.$route.params.id +'.'+ this.myinfo.id
          } else {
            this.channelid = this.chantype + '.' + this.myinfo.id + '.' + this.$route.params.id
          }

          axios.post('/postmessage', { to: this.$route.params.id, from: this.myinfo.id, message: this.message, ug: this.$route.params.ug, attach: this.idBaseFile, datesend: timenow, read: 2 , room_id: this.channelid, online: this.UserPostOnline })
            .then(response => {

                // очищаем вложения
                this.idBaseFile = 0;
                this.$root.$emit('ListFileClean')

            })
            .catch(error => {
              this.$bvModal.msgBoxOk('Ошибка при отправке.')
              console.log(error)
            });

            // чистим сообщение
            this.message = ''
        }
   }
 }
</script>

<style scoped>



.inputsend {
  margin :4px;
  outline-offset: 0;
  border: 0;
}
.inputsend:focus  {
 outline: none !important;
}

.sendbut {
  padding-top:5px;
  padding-right:4px;
}
.sendbut:active {
 opacity: .4;
}

.filebut {
  padding-left:3px;
  padding-top:6px;
}
.filebut:active {
  opacity: .4;
}




.butdown {
    background-color: #ffffff;
    width: 100%;
}

.fileu{
  display: inline-block;
  background-color: indigo;
  color: white;
  padding: 0.5rem;
  font-family: sans-serif;
  border-radius: 0.3rem;
  cursor: pointer;
  margin-top: 1rem;
}

</style>
