<template>
  <div class="login-form">
    <b-row>
      <b-col>
        <b-card>
          <b-card-header>
            <h3 class="text-center text-uppercase">
              <b-icon-box-arrow-in-right></b-icon-box-arrow-in-right>
              <span class="ml-2">Login</span>
            </h3>
          </b-card-header>
          <b-card-text>
            <login-form />
          </b-card-text>
          <b-card-footer class="text-ri">
            <p class="mt-4 mb-1 text-muted">Non sei iscritto? <router-link to="/register">Iscriviti</router-link></p>
            <p class="text-muted"><router-link to="/forgot-password">Password dimenticata?</router-link></p>
          </b-card-footer>
        </b-card>
        <p class="copy text-center text-muted">{{ app_name }} &copy; {{ year }}</p>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import LoginForm from '@/components/auth/loginForm'

export default {
  name: 'Login',
  components: {LoginForm},
  data() {
    return {
      app_name: process.env.VUE_APP_NAME,
      year: new Date().getFullYear()
    }
  }
}
</script>
<style lang="scss" scoped>
  .login-form {
    max-width: 450px;
    margin:auto;
    padding:60px 0;
  }
</style>
