<template>




  <div class="row border">
<!-- d-none d-md-block  d-none d-md-block-->
    <div class="col-md-4 my-1">
      <p class="font-weight-bold">
        <b-avatar variant="light"></b-avatar> {{ this.myinfo.name }}
      </p>
    </div>
    <div class="col my-1">

      <div class="clearfix">

        <div class="row">
          <div class="col" style="flex: 0 0 70px;">
             <b-avatar variant="success" v-if="this.UserUpOnline === 1"></b-avatar>
            <b-avatar variant="secondary" v-else></b-avatar>
          </div>
          <div class="col">
            <div class="row">
              <div class="col font-weight-bold">{{this.UserUpName}}</div>
              <div class="w-100"></div>
              <div class="col">
                <span v-if="this.isActive">{{ isActive.name }} набирает сообщение...</span>
                <span v-else-if="this.UserPostOnline"> Сейчас в чате</span>
                <span v-else></span>
              </div>
            </div>
          </div>
          <div class="col"><b-spinner v-if="this.loadingchat" class="float-right" label="Floated Right"></b-spinner></div>
        </div>






      </div>


    </div>
        <div class="w-100"></div>

    <div class="col-md-4">
      <UsersWin
      class="userwin"
      :myinfo="this.myinfo"
      ref="hideUserChatOn"
      ></UsersWin>
    </div>



    <div class="col">

      <div v-bind:style="styleObject">
        <ChatWin
        :chattextin="chattext"
        :myinfo="this.myinfo"
        ref="scrollToDownWin"
        ></ChatWin>
    </div>

      <FormSubmit v-if="this.$route.params.id"
      :myinfo="this.myinfo"
      ></FormSubmit>
    </div>
  </div>





</template>

<script>
//import axios from 'axios';
import moment from 'moment'
import UsersWin from '../components/UsersWin'
import FormSubmit from '../components/FormSubmit'
import ChatWin from '../components/ChatWin';


  export default {
    components: {
      UsersWin,
      FormSubmit,
      ChatWin
    },
      props: ['myinfo'],
  data() {

    return {
      seen: true,
      chattext: '',
    //id: null,
      dataread: null,
      sf: null,
      username: null,
      useronline: null,
      userid: null,
      userlast: null,
      group: null,
      loadingchat: false,
      idfiles: null,
      UserUpName: null,
      UserUpOnline: null,
      UserUpId: null,
      isActive: false,
      UserPostOnline: false,

      styleObject: {
        opacity: '1.0',
        //'pointer-events' : 'none'
      }

    };
  },
  methods : {
    okDelMes(){
      alert('sd');
    },
    hideChatOff() {
      this.styleObject.opacity = '0.4',
      this.styleObject.pointerEvents = 'none'
      this.loadingchat = true
    },
    hideChatOn() {
          this.styleObject.opacity = '1.0',
          this.styleObject.pointerEvents = 'visible'
          this.loadingchat = false
          //alert('v')
    },




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


    getMessage(){

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
                this.hideChatOn(),
                this.$refs.hideUserChatOn.hideUserChatOn(),
                this.scrollToDown()

          //  }
        ));
      }

    }
  },


    mounted() {

      // пользователь в чате
      this.$root.$on('UserPostOnline', (param) => {
        this.UserPostOnline = param;
      })

      // печатает сообщение
      this.$root.$on('PrintMess', (param) => {
        this.isActive = param;
      })

      /// имя сверху
      this.$root.$on('NameUserUp', (id, name, online) => {
        console.log(online);
        this.UserUpId = id;
        this.UserUpName = name;
        this.UserUpOnline = online;
        //alert(nameUser.name);
      }),

      this.$root.$on('getMessInChat', () => {
          this.getMessage()
          //this.loadUserInfo();
          this.usertext = ''
          this.hideChatOff()
          //this.loadingchat = true
      }),

      //this.$root.$on('NameUserUp', (userid,username,useronline,userlast,group) => {
      //    this.userid = userid,
      //    this.username = username,
      //    this.useronline = useronline,
      //    this.userlast = userlast
      //    this.group = group
      //}),

      //window.setInterval(() => {
      //        this.getMessage(false);
      //}, 10000)

      //this.startTimerChat()
      this.getMessage()


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
