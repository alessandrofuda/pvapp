<template>
  <div class="register-form">
    <b-row>
      <b-col>
        <b-card>
          <b-card-header>
            <h3 class="text-center text-uppercase">
              <b-icon-box-arrow-in-right></b-icon-box-arrow-in-right>
              <span class="ml-2">Register</span>
            </h3>
          </b-card-header>
          <b-card-text>
            <register-form />
          </b-card-text>
          <b-card-footer class="text-ri">
            <p class="mt-4 mb-1 text-muted">Sei già iscritto? <router-link to="/login">Login</router-link></p>
          </b-card-footer>
        </b-card>
        <p class="copy text-center text-muted">{{ app_name }} &copy; {{ year }}</p>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import RegisterForm from '@/components/auth/registerForm'

export default {
  name: 'Register',
  components: {RegisterForm},
  data() {
    return {
      app_name: process.env.VUE_APP_NAME,
      year: new Date().getFullYear()
    }
  }
}
</script>

<style lang="scss" scoped>
.register-form {
  max-width: 500px;
  margin:auto;
  padding:60px 0;
}
</style>
