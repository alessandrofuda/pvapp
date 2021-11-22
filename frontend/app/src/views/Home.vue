<template>
  <basic-layout>
    <div class="home">
      <img alt="Vue logo" src="../assets/logo.png">
      <HelloWorld msg="Welcome to Your Vue.js App"/>
    </div>
  </basic-layout>
</template>

<script>
// @ is an alias to /src
import BasicLayout from './layouts/basicLayout'
import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'Home',
  components: {BasicLayout, HelloWorld }
}
</script>
