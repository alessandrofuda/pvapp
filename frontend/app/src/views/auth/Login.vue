<template>
  <!--auth-layout class="login-form"-->
      <b-container class="login-form">
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
                <p class="mt-4 mb-1 text-muted">Non sei iscritto? <a href="/api/register">Iscriviti</a></p>
                <p class="text-muted"><a href="/api/forgot-password">Password dimenticata?</a></p>
              </b-card-footer>
            </b-card>
            <p class="copy text-center text-muted">PvApp &copy; {{ year }}</p>
          </b-col>
        </b-row>
      </b-container>
  <!--/auth-layout-->
</template>

<script>
// import AuthLayout from '../layouts/authLayout'
import LoginForm from '@/components/auth/loginForm'

export default {
  name: 'Login',
  components: {LoginForm}, // AuthLayout,
  data() {
    return {
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
