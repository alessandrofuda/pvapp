<template>
  <div class="basic-layout">
    <b-container>
      <nav id="nav" class="nav">
        <span v-if="isLogged" class="mr-2">Hello <b>{{ name }}</b>, </span>
        <span>
          <router-link to="/">Home</router-link> |
          <router-link to="/about">About</router-link> |
        </span>
        <span v-if="isLogged">
          <a href="javascript:void(0)" @click.prevent="logout">Logout</a>
        </span>
        <span v-else>
          <router-link to="/login">Login</router-link> |
          <router-link to="/register">Register</router-link>
        </span>
      </nav>
      <main class="basic-layout-main">
        <b-alert class="my-5 py-3" :show="alert.show" :variant="alert.variant"  dismissible>
          {{ alert.msg }}
        </b-alert>
        <router-view></router-view>
        <b-overlay :show="loading" no-wrap></b-overlay>
      </main>
      <footer class="basic-layout-footer">
        {{ app_name }} &copy; {{ year }}
      </footer>
    </b-container>
  </div>
</template>
<script>
import Store from '@/store'
import { mapActions } from "vuex"
// import router from '@/router';

export default {
  name: 'basicLayout',
  data() {
    return {
      // name: null,
      app_name: process.env.VUE_APP_NAME,
      year: new Date().getFullYear(),
      alert: {
        show: false,
        variant: null,
        msg: null
      },
      loading: false
    }
  },
  computed: {
    isLogged() {
      return Store.state.auth.authenticated || false
    },
    name() {
      return Store.state.auth.user.name || null
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
        this.signOut()
        // router.push({name:'dashboard'}).catch(() => { })
        this.alert.variant = 'success'
        this.alert.msg = 'Logged Out Successfully'
      })
      .catch(({response}) => {
        console.error(response)
        this.alert.variant = 'danger'
        this.alert.msg = response ? Object.values(response.data.errors).join(' ') : 'General Error'
      })
      .finally(() => {
        this.loading = false
        this.alert.show = true
      })

    }
  }
}
</script>
