<template>
  <div class="forgot-password-form">
    <b-row>
      <b-col>
        <b-card>
          <b-card-header>
            <h3 class="text-center text-uppercase">
              <b-icon-shield-lock></b-icon-shield-lock>
              <span class="ml-2">Password Recovery</span>
            </h3>
          </b-card-header>
          <b-card-text>
            <forgot-password-form />
          </b-card-text>
          <b-card-footer class="text-ri">
            <p class="mt-4 mb-1 text-muted">Non sei iscritto? <router-link to="/register">Iscriviti</router-link></p>
            <p class="text-muted">Sai la password? <router-link to="/forgot-password">Login</router-link></p>
          </b-card-footer>
        </b-card>
        <p class="copy text-center text-muted">{{ app_name }} &copy; {{ year }}</p>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import ForgotPasswordForm from '@/components/auth/forgotPasswordForm'

export default {
  name: 'ForgotPassword',
  components: {ForgotPasswordForm},
  data() {
    return {
      app_name: process.env.VUE_APP_NAME,
      year: new Date().getFullYear()
    }
  }
}
</script>

<style lang="scss" scoped>
  .forgot-password-form {
    max-width: 450px;
    margin:auto;
    padding:60px 0;
  }
</style>
