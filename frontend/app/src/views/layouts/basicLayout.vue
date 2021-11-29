<template>
  <div class="basic-layout">
    <b-container>
      <nav id="nav" class="nav">
        <router-link to="/">Home</router-link> |
        <router-link to="/about">About</router-link> |
        <a v-if="isLogged" class="b__tn" href="#" @click.prevent="logout">Logout</a>
        <router-link v-else to="/login">Login</router-link>
      </nav>
      <main class="basic-layout-main">
        <router-view></router-view>
      </main>
      <footer class="basic-layout-footer">
        &copy; Awesome Company
      </footer>
    </b-container>
  </div>
</template>
<script>
import Store from '@/store'
import { mapActions } from "vuex"
import router from '@/router';

// console.log(Store.state.auth.authenticated)

export default {
  name: 'basicLayout',
  data() {
    return {
      // isLogged: false
    }
  },
  computed: {
    isLogged() {
      return Store.state.auth.authenticated || false
    }
  },
  methods: {
    ...mapActions({
      signOut:'auth/logout'
    }),
    async logout(e) {
      e.preventDefault()
      this.loading = true

      await window.axios.post('/logout')
      .then(() => {
        //if(status === 200) {
          this.signOut()
          router.push({name:'dashboard'})
        // }else {
        //   this.alert.variant = 'danger'
        //   this.alert.msg = `Invalid status code on login: ${status}`
        // }

      })
      .catch(() => {
        // this.alert.variant = 'danger'
        // this.alert.msg = response ? Object.values(response.data.errors).join(' ') : 'General Error'
      })
      .finally(() => {
        this.loading = false
        // this.alert.show = true
      })

    }
  }
}
</script>
