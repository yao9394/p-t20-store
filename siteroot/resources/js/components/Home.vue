<template>
<div class="row justify-content-center align-items-center">
  <div class="jumbotron">
    <p v-if="!Loggedin">Welcome to acg portal</p>
    <p v-if="Loggedin">Loggedin</p>
  </div>
</div>
</template>
<script>
    export default {
      computed: {
        Loggedin: function () {
          const token = localStorage.getItem('user-token');
          if (token) {
            return true;
          }

          return false;
        } 
      }
    }
</script>