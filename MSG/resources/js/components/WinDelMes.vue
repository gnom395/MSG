<template>

  <div>
  <div class="mb-2">
   <b-button @click="showMsgBoxOne">Вы уверены, что хотите удалить сообщение? {{this.idmes}}</b-button>
   Return value: {{ String(boxOne) }}
  </div>
  <div class="mb-1">
   <b-button @click="showMsgBoxTwo">msgBoxConfirm with options</b-button>
   Return value: {{ String(boxTwo) }}
  </div>
</div>

</template>

<script>
  export default {

    props: ['idmes'],

  methods: {
    ok(){
      alert('sd');
    },

  }
  }
</script>

<style scoped>

</style>
