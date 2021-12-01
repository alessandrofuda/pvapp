<template>
  <div class="reset-password">
    <b-row>
      <b-col>
        <b-card>
          <b-card-header>
            <h3 class="text-center text-uppercase">
              <b-icon-key></b-icon-key>
              <span class="ml-2">Reset Password</span>
            </h3>
          </b-card-header>
          <b-card-text>
            <reset-password-form />
          </b-card-text>
          <b-card-footer class="text-ri">
            <p class="mt-4 mb-1 text-muted">Hai la password? <router-link to="/login">Login</router-link></p>
          </b-card-footer>
        </b-card>
        <p class="copy text-center text-muted">{{ app_name }} &copy; {{ year }}</p>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import ResetPasswordForm from '@/components/auth/resetPasswordForm'

export default {
  name: 'ResetPassword',
  components: {ResetPasswordForm},
  data() {
    return {
      app_name: process.env.VUE_APP_NAME,
      year: new Date().getFullYear()
    }
  }
}
</script>

<style lang="scss" scoped>
  .reset-password {
    max-width: 500px;
    margin:auto;
    padding:60px 0;
  }
</style>
