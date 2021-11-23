<template>
  <auth-layout class="login-form">
      <b-container>
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
                <b-form @submit="login" novalidate>
                  <b-form-group id="email" label-for="email">
                    <b-icon-envelope-fill></b-icon-envelope-fill>
                    <b-form-input
                        id="email"
                        v-model="form.email"
                        :state="validationEmail"
                        type="email"
                        placeholder="Email"
                        aria-de_____scribedby="input-1-live-feedback"
                        req___uired
                    ></b-form-input>
                    <b-form-invalid-feedback :state="validationEmail" i__d="input-1-li___ve-feedback">This is a required field and must be an email.</b-form-invalid-feedback>
                  </b-form-group>
                  <b-form-group id="password" label-for="password">
                    <b-icon-lock-fill></b-icon-lock-fill>
                    <b-form-input
                        id="password"
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        required
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group>
                    <b-button size="lg" class="w-100" type="submit" variant="primary">Login</b-button>
                  </b-form-group>
                </b-form>
              </b-card-text>
              <b-card-footer class="text-ri">
                <p class="mt-4 mb-1 text-muted">Non sei iscritto? <a href="/register">Iscriviti</a></p>
                <p class="text-muted"><a href="/forgot-password">Password dimenticata?</a></p>
              </b-card-footer>
            </b-card>
            <p class="copy text-center text-muted">PvApp &copy; {{ year }}</p>
          </b-col>
        </b-row>
      </b-container>
  </auth-layout>
</template>

<script>
import AuthLayout from '../layouts/authLayout'
// import { email, required } from '@vuelidate/validators'
// import useVuelidate from '@vuelidate/core'





// TODO disinstallare vuelidate e usare la validazione "core" di bootstrap-vue (con computed()..)
// https://bootstrap-vue.org/docs/components/form --> Feedback helper





export default {
  name: 'Login',
  components: {AuthLayout},
  data() {
    return {
      form: {
        email: '',
        name: '',
      },
      year: new Date().getFullYear()
    }
  },
  computed: {
    validationEmail() {
      return this.form.email.length == 0 ? null : true // null,true,false // this.userId.length > 4 && this.userId.length < 13
    },
    validationPassword() {
      return null // null,true,false // this.userId.length > 4 && this.userId.length < 13
    }
  },
  // setup: () => ({ v$: useVuelidate() }),
  // validations () {
  //   return {
  //     form: {
  //       email: { required, email },
  //       password: { required }
  //     }
  //   }
  // },
  methods: {
    login(event) {
      event.preventDefault()
      alert(JSON.stringify(this.form))
    }
  }
}
</script>

<style lang="scss" scoped>
  .login-form {
    max-width: 450px;
    margin:auto;
    padding:60px 0;
    .form-group {
      padding:20px 0;
    }
  }
</style>
