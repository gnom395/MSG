<template>
<div>

  <div class="row">
    <div class="col-md-4 d-none d-md-block">

      <UsersWin
      class="userwin"
      :usermy="this.user"
      ref="hideUserChatOn"
      ></UsersWin>

    </div>
    <div class="col">

      <ChatWin
      :chattextin="chattext"
      :usermy="this.user"
      ref="scrollToDownWin"
      ></ChatWin>

      <FormSubmit v-if="this.$route.params.id"
      :usermy="this.user"
      ></FormSubmit>
    </div>
  </div>



<WinFiles :idfiles="idfiles"></WinFiles>

<!--
        <div class="upname bg-white border">

      <div v-if="this.$route.params.id">
        <div v-if="this.group">
          <p class="font-weight-bold">
            <b-avatar variant="primary" text="Гр" class="my-1"></b-avatar>
            {{ this.username }} (группа)
          </p>
        </div>
        <div v-else>
          <p class="font-weight-bold">
            <b-avatar variant="success" v-if="this.useronline === 1" class="my-1"></b-avatar>
            <b-avatar variant="secondary" class="my-1" v-else></b-avatar>
                     {{ this.username }}  {{ this.userlast }} {{ this.group }}
          </p>
        </div>
      </div>
        </div>




    <div class="col  border">
      	<div class="userwin"><UsersWin v-bind:myid="this.myid"  v-bind:myip="this.myip" v-bind:myname="this.myname"></UsersWin></div>
    </div>
-->





</div>


</template>

<script>
//import axios from 'axios';
import moment from 'moment'
import UsersWin from '../components/UsersWin'
import FormSubmit from '../components/FormSubmit'
import WinFiles from '../components/WinFiles';
import ChatWin from '../components/ChatWin';

  export default {
    components: {
      UsersWin,
      FormSubmit,
      WinFiles,
      ChatWin
    },
      props: ['user'],
  data() {

    return {
       seen: true,
      chattext: '',
    //id: null,
      dataread: null,
      sf: null,
    //  loadingchat: true,
      username: null,
      useronline: null,
      userid: null,
      userlast: null,
      group: null,
      showchat: false,
      idfiles: '',
      //timestamp: null,
      //polling: '',
      chId: ''


      //obj: this.$route.params.id,
      //objold: obj
    };
  },
  methods : {




    //startTimerChat(){
    //  this.polling = setInterval(() => {
    //    this.getMessage(false)
    //    console.log('interval')
    //  }, 10000)
      //alert('start')
    //},

    //clearTimerChat(){
    //  clearInterval(this.polling)
      //alert('stop')
    //},


  //  getNow() {
  //    const today = new Date();
  //    const date = today.getDate()+'.'+(today.getMonth()+1)+'.'+today.getFullYear();
  //    const time = today.getHours() + ":" + today.getMinutes();
  //    /// + ":" + today.getSeconds();
    //  const dateTime = time +' '+ date;
  //    this.timestamp = moment(String(dateTime)).format('hh:mm MM.DD.YYYY')
      //this.timestamp = dateTime;
  //  },


    scrollToDown() {
      /// вызываем функцию из дочернего компонента
      this.$refs.scrollToDownWin.scrollToDown()

      //this.$refs.webchatconn.webchatconn()

    //  this.$root.$emit('scrollToDownWin')

    //  const el = this.$el.getElementsByClassName('scroll')[0];
    //  if (el) {
    //    el.scrollIntoView({behavior: "smooth", block: "end"});
      //el.scrollIntoView(false);
    //  }
    },

    //oldChat(res){

    //  if(this.sf != res) {
        /// если id поменялся
      //  this.scrollToDown()
      //  alert(this.sf +' '+ res)
      //}

      //this.sf = res

    //},
    getMessage(getparam){

      if(typeof(this.$route.params.id) !== 'undefined' ) {

      //alert('/getmessage?ug' + this.$route.params.ug + '&to=' + this.$route.params.id);
        axios
          .get('/getmessages?ug=' + this.$route.params.ug + '&to=' + this.$route.params.id)
          .then(response => (
            this.chattext = response.data
            //this.oldChat(response.data[0].id)
          ))
          .catch(error => console.log(error))
          //.finally(() => (
          .then(response => (
          //  if(response.data.status){
                this.showchat = true,
                //this.$root.$emit('hideUsers')
                this.$refs.hideUserChatOn.hideUserChatOn()

          //  }
        ));
      }

      if(getparam == true) {
        window.setTimeout(() => {
                this.scrollToDown()
        }, 1000)
      //  alert(getparam)
      }
    }
  },


    mounted() {


      //if(typeof(this.$route.params.id) !== 'undefined' ) {
      //  this.loadUserInfo();
      //}


      this.$root.$on('getMessInChat', () => {
          this.getMessage(true)
          //this.loadUserInfo();
      //    this.usertext = '',
      //    this.showchat = false
      }),
    //  this.$root.$on('ChatInPost', () => {
      //    this.getMessage(true);

        // window.setTimeout(() => {
        //          this.scrollToDown()
        //  }, 1000)
          //this.usertext = '',
          //this.showchat = false
      //}),
    //  this.$root.$on('CleanChat', () => {
    //      this.cleanChatLoad();
    //  }),
      this.$root.$on('NameUserUp', (userid,username,useronline,userlast,group) => {
          this.userid = userid,
          this.username = username,
          this.useronline = useronline,
          this.userlast = userlast
          this.group = group
      }),

      //window.setInterval(() => {
      //        this.getMessage(false);
      //}, 10000)

      //this.startTimerChat()
      this.getMessage(true)


    },
  //  beforeDestroy () {
	//     clearInterval(this.polling)
  //  }

  }
</script>

<style scoped>



.userwin {
  overflow-y: scroll;
 height: 450px; /* Высота блока */
 width: 100%;
 padding: 0;
}



</style>
