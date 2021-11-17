<template>

  <!--
  <div>
    <b-list-group>
      <div v-for="showe in usertext" @click="handleClick">
        <b-list-group-item class="d-flex align-items-center">
          <b-avatar class="mr-3"></b-avatar>
          <span class="mr-auto">
            <router-link v-if="showe.is_group === 1" :to="'/group/' + showe.groups_id" class="active" title='Сообщение всем в группе'><b>{{ showe.name }}</b></router-link>
            <router-link v-else :to="'/user/' + showe.id">
              {{ showe.name }}
          </router-link>
          </span>
          <b-badge v-if="showe.new_mes">{{ showe.new_mes }}</b-badge>
        </b-list-group-item>
      </div>
    </b-list-group>
  </div>
-->

<div v-bind:style="styleObject">


  <div class="list-group">
  <div v-for="showe in usertext" @click="handleClick(showe.id,showe.name,showe.online,showe.last,showe.groups_id)">

      <!-- это для имени пользователя над чатом -->
        <div v-if="showe.id === useridget" v-on="handleClickLoad(showe.id,showe.name,showe.online,showe.last,showe.groups_id)"></div>

      <!-- это для группы над чатом -->

      <div v-if="groupidget === 'group'">
        <div v-if="showe.groups_id === useridget" v-on="handleClickLoad(showe.id,showe.name,showe.online,showe.last,showe.groups_id)"></div>
      </div>

    <router-link v-if="showe.is_group === 1" :to="'/group/' + showe.groups_id" class="list-group-item list-group-item-action active" title='Сообщение всем в группе'>
      <b>{{ showe.name }}</b>
    </router-link>
    <router-link v-else :to="'/user/' + showe.id" class="list-group-item list-group-item-action">

      <b-avatar variant="success" v-if="showe.online === 1"></b-avatar>
      <b-avatar variant="secondary" v-else></b-avatar>
      <!--
      <img v-if="showe.online === 1" src="/assets/img/online.png">
      <img v-else src="/assets/img/offline.png">
    -->
      {{ showe.name }}
      <span v-if="showe.new_mes" class="badge badge-primary badge-pill">{{ showe.new_mes }}</span>


  </router-link>
  </div>
  </div>
  <b-spinner v-if="this.loading" label="Spinning" style="position: absolute;top: 50%;left: 50%;"></b-spinner>


</div>

</template>

<script>
//import axios from 'axios';

  export default {

  data() {

    return {
      search: '',
      usertext: null,
      toastCount: 0,
      useridget: parseInt(this.$route.params.id),
      groupidget: this.$route.params.ug,
      loading: true,
      room_id: 999,
      data: '',
      pushfile: [],

      styleObject: {
        opacity: '1.0',
        //'pointer-events' : 'none'
      }
    };
  },
      props: ['myinfo'],


  mounted() {
    this.loading = true

    this.getusers()

      if(this.$route.params.id !== 'undefined') {
        /// ChatWin
        window.setTimeout(() => {

          this.$root.$emit('webchatconn',this.$route.params.id);

          //this.usertext
          //handleClick(showe.id,showe.name,showe.online,showe.last,showe.groups_id)
          /// загружаем сообщения в чат ChatWin
          this.$root.$emit('getMessInChat');
          //this.hideUserChatOff()
        }, 1000);

        //console.log(this.$route.params.id);

      }
    //this.$root.$emit('webchatconn',userid)

    //window.setInterval(() => {
    //  this.getusers()
    //}, 15000)

    this.channel
        .here((users) => {

          //const usersjson = JSON.stringify(users);

          //console.log('here' + usersjson);
          /// ставим статус онлайн все кто в сети
          for (var i = 0; i < users.length; i++){

              console.log('user here' + users[i].id);

              for (var y = 0; y < this.usertext.length; y++){
                if (users[i].id == this.usertext[y].id ){
                  this.usertext[y].online = 1;
                  /// отправляем данные о пользователе в index
                  //this.$root.$emit('NameUserUp',users[i].id,users[i].name,users[i].online);

                  console.log('set online ' + this.usertext[y].id);
                  break;
                }
              }

          }


        })
        /// пользователь зашел в чат
        .joining((user) => {

          /// ставим статус онлайн
          for (var i = 0; i < this.usertext.length; i++){
            if (user.id == this.usertext[i].id ){
              this.usertext[i].online = 1;
              //console.log(this.usertext[i].online);
              break;
            }
          }
            //this.usertext[user.id].online = 1
            //this.$set(this.usertext, 'online', 1)
            console.log(user.name + ' ' + user.id + ' в сети');
        })
        /// пользователь вышел из чата
        .leaving((user) => {

          /// ставим статус офлайн
          for (var i = 0; i < this.usertext.length; i++){
            if (user.id == this.usertext[i].id ){
              this.usertext[i].online = 0;
              //console.log(this.usertext[i].online);
              break;
            }
          }

            console.log(user.name + ' не в сети');
        })
        .error((error) => {
            console.error(error);
        })
        .listen('PresenceChat', ({data}) => {
          /// новые сообщения
          // проверяем нам ли сообщение и если открыт чат  не будем ставито статус новые

          //console.log(data.to +' '+ this.myinfo.id +' '+data.from + ' '+ this.$route.params.id)

          if(data.to == this.myinfo.id && data.from != this.$route.params.id) {

            /// если новые письма уже есть
              for (var j = 0; j < this.usertext.length; j++){
                  //console.log(data)

                if (this.usertext[j].new_mes && data.from == this.usertext[j].id){
                  this.usertext[j].new_mes++

                  //console.log('edit'+this.usertext[j].id)
                  //    this.usertext.splice(h, 1);

                  break;
                } else {

                  this.usertext.unshift({
                    id: data.from,
                    name: data.name,
                    new_mes: 1,
                    online: 1
                  })
                //console.log('add'+this.usertext[j].id)
                break;

                  //this.usertext.unshift(data.body)
                }
              }
            /// если нет новых писам то создаем запись сверху
         }

        });

  },
  computed:{
    channel(){
      return window.Echo.join('room.' + this.room_id)
    }
  },
  destroyed(){
    window.Echo.leave('room.' + this.room_id)
  },
  methods: {

    getusers(){
      axios
      .get('/getusers?myid='+this.myinfo.id)
      .then(response => (
        this.usertext = response.data,
        this.loading = false,
        this.getUserName(response.data)
      ))
      .catch(error => console.log(error));

    },
    getUserName(usert){

      for (var j = 0; j < usert.length; j++){
        if (this.$route.params.id == usert[j].id ){
          //console.log(usert[j]);
          /// отправляем имя в шапку
          this.$root.$emit('NameUserUp',usert[j].id,usert[j].name,usert[j].online);
          //this.UserPostOnlinefun(true);
            break;
        }
      }
    },
    makeToast(name) {
      this.toastCount++
      this.$bvToast.toast(`This is toast number ${this.toastCount}`, {
        title: 'BootstrapVue Toast'+name,
        autoHideDelay: 10000
      })
    },
    hideUserChatOff() {
      this.styleObject.opacity = '0.4',
      this.styleObject.pointerEvents = 'none'
    },
    hideUserChatOn() {
          this.styleObject.opacity = '1.0',
          this.styleObject.pointerEvents = 'visible'
          //alert('v')
    },
    handleClick (userid,username,useronline,userlast,group) {

      /// делаем id часного канала
      this.$root.$emit('webchatconn',userid)

      /// загружаем сообщения в чат ChatWin
      this.$root.$emit('getMessInChat')

      //this.$root.$emit('CleanChat')
      /// отправляем данные о пользователе в index
      this.$root.$emit('NameUserUp',userid,username,useronline);

      //this.$root.$emit('ScrollDown')
      this.hideUserChatOff()
      //this.useridget = null

        /// удаляем json кнопки если есть новые сообщения
        for (var h = 0; h < this.usertext.length; h++){
          if (userid == this.usertext[h].id && this.usertext[h].new_mes){

            this.usertext.splice(h, 1);
            //console.log(this.usertext[h]);
            break;
          }
        }

    },
    handleClickLoad (userid,username,useronline,userlast,group) {
      this.$root.$emit('NameUser',userid,username,useronline,userlast,group)
    }
  }

}


</script>

<style scoped>

</style>
