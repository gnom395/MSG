<template>
    <div>
        <h1>Блог</h1>
    </div>
    </template>

<script>
    export default {
        name: "Changename",

        data() {
          return {
            orderId: 1
          };
        },
        mounted() {
          Echo.private(`orders.${this.orderId}`)
        .listen('orderupdate', (e) => {
            console.log(e.order);
        });
        }
    }

</script>

<style scoped>
</style>
