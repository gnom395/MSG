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
        </label>
      </b-input-group-prepend>


    </b-input-group>


<!--
      <div>
          <label class="btn btn-primary">
            <i class="fa fa-image">Фаил</i><input type="file" style="display: none;"  name="image" id="file" ref="file" @change="submitFile()">
          </label>

        <textarea class="form-control" v-model="message" rows="1" ref="message" v-on:keyup.ctrl.enter="submit"></textarea>

          <b-button v-else="loading" type="submit" variant="primary">Send</b-button>

        </div>
    -->
    <!--<input type="file" id="file" ref="file" @change="submitFile()"/>-->
      <input type="text" v-model="idBaseFile">
      <!--<input type="file" id="file" ref="file" @change="submitFile()">-->

      <!-- <button @click="submitFile()">Submit</button> -->



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
        idBaseFile: 0

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
     AttachFile(filesFinish){
       console.log(filesFinish);
     },

     /// печатает сообщение
     UserPrintMess(){

          window.Echo.join('room.' + this.channelid)
           .whisper('typing',{
             name: this.myinfo.name
           })
     },

  //   sendfiles(get){
  //     this.filesend = get
  //     this.attachfile = ''
       //this.attachfiletmp =''

  //     for (let key in this.filesend) {
  //       if(this.filesend.hasOwnProperty(key)){
  //         console.log(`${key} : ${this.filesend[key]['id']}`)
//           if(key == 0) {
//             this.attachfile = this.filesend[key]['id'];
  //         } else {
  //           this.attachfile = this.attachfile + ',' + this.filesend[key]['id'];
  //         }
  //       }
  //     }
       //if(this.attachfile == '') {
      //   this.attachfile = this.attachfiletmp;
       //} else {
        // this.attachfile = this.attachfile + ',' + this.attachfiletmp;
       //}

  //   },
//     submitFile(){

//      this.file = this.$refs.file.files[0];


      //axios.post('/postmessage', { message: this.message, to: this.$route.params.id, ug: this.$route.params.ug })
//       let formData = new FormData();
//       formData.append( 'file', this.file );
//       formData.append( 'fromid', this.myinfo.id );
//       formData.append( 'attach', this.filesend );
//       axios.post( '/postfile',
//        formData,
//          {
//            headers: {
//                'Content-Type': 'multipart/form-data'
//            }
//          }

//        ).then(response => {
//            console.log('SUCCESS!!');
//            this.usertext = response.data

//            this.listfile.push({
//              id: response.data.id,
//              title: this.file.name
//            }),
//            this.$root.$emit('AddFileList',response.data.filename,response.data.id)


    //      })
  //      .catch(error => console.log(error))

//      },

      handleFileUpload(){
        this.file = this.$refs.file.files[0];
      },


      async submit(){

      /// остановили setInterval ждем отправки пост и запускаем опять
      this.$emit('clearTimerChat')

      // пишем новое сообщение пока не загрузился чат
      this.$emit('callNewMessPrint',this.$route.params.id,this.message,this.attachfile)
      //this.$refs.message.newMessPrint()

      /// ставим фокус на инпуте
        this.$refs.message.focus()

        if(this.message == ''){
               alert('пусто')
        } else {


          try {

                const today = new Date();
                const timenow = today.getHours() + ":" + today.getMinutes();

                /// делаем channelid
                if(this.$route.params.id < this.myinfo.id) {
                  this.channelid = this.$route.params.id +'.'+ this.myinfo.id
                } else {
                  this.channelid = this.myinfo.id + '.' + this.$route.params.id
                }

                //console.log(this.channelid);
            axios.post('/postmessage', { to: this.$route.params.id, from: this.myinfo.id, message: this.message, ug: this.$route.params.ug, attach: this.idBaseFile, datesend: timenow, read: 2 , room_id: this.channelid, online: this.UserPostOnline })
            .then(response => {
                //this.message = '';
                //this.message.push(response.message)

                //alert(this.channelid)
                //this.$root.$emit('ChatInPost');

                // очищаем вложения
                this.filesend = ''
                this.attachfile = 0
                this.$root.$emit('ListFileClean')


                //console.log(response.data)
            })

          } catch(error) {
             console.log(error)
          }
          this.message = ''
          //this.$root.$refs.Chat.loadChat();


      }
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
