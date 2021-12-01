<template>
  <b-form @submit="resetPassword">
    <input type="hidden" name="token" :value="form.token">
    <b-form-group id="email-group" label-for="email">
      <b-icon-envelope-fill></b-icon-envelope-fill>
      <b-form-input
          id="email"
          v-model="$v.form.email.$model"
          :state="validateState('email')"
          type="email"
          placeholder="Email"
          aria-describedby="email-live-feedback"
      ></b-form-input>
      <b-form-invalid-feedback id="email-live-feedback">This is a required field and must be an email.</b-form-invalid-feedback>
    </b-form-group>
    <b-form-group id="password-group" label-for="password">
      <b-icon-file-lock-fill></b-icon-file-lock-fill>
      <b-form-input
          id="password"
          v-model="$v.form.password.$model"
          :state="validateState('password')"
          type="password"
          placeholder="Type new password"
          aria-describedby="password-live-feedback"
      ></b-form-input>
      <b-form-invalid-feedback id="password-live-feedback">This is a required field (min {{ form.pswMinLength }} characters).</b-form-invalid-feedback>
    </b-form-group>
    <b-form-group id="password-confirmation-group" label-for="password_confirmation">
      <b-icon-exclamation-triangle-fill></b-icon-exclamation-triangle-fill>
      <b-form-input
          id="password-confirmation"
          v-model="$v.form.password_confirmation.$model"
          :state="validateState('password_confirmation')"
          type="password"
          placeholder="Retype and Confirm Password"
          aria-describedby="password-confirmation-live-feedback"
      ></b-form-input>
      <b-form-invalid-feedback id="password-confirmation-live-feedback">Passwords must be identical.</b-form-invalid-feedback>
    </b-form-group>
    <b-button size="lg" class="w-100" type="submit" variant="primary">Reset Password</b-button>
    <b-alert class="my-5 py-3" :show="alert.show" :variant="alert.variant"  dismissible>
      {{ alert.msg }}
    </b-alert>
    <router-link v-if="loginBtn" class="btn btn-block btn-success mt-5 text-uppercase" to="/login">Vai al Login</router-link>
    <b-overlay :show="loading" no-wrap></b-overlay>
  </b-form>
</template>
<script>
import { validationMixin } from "vuelidate"
import { required, minLength, maxLength, email, sameAs } from "vuelidate/lib/validators"
// import { mapActions } from "vuex"

export default {
  name: 'resetPasswordForm',
  mixins: [validationMixin],
  data() {
    return {
      form: {
        token: this.$route.params.token || null,
        email: null,
        password: null,
        password_confirmation: null,
        pswMinLength: 8
      },
      alert: {
        show: false,
        variant: null,
        msg: ''
      },
      loading: false,
      loginBtn: false
    }
  },
  validations() {
    return {
      form: {
        email: {
          required,
          email,
          maxLength: maxLength(150)
        },
        password: {
          required,
          minLength: minLength(this.form.pswMinLength),
          maxLength: maxLength(150)
        },
        password_confirmation: {
          required,
          sameAsPassword: sameAs('password')
        }
      }
    }
  },
  methods: {
    // ...mapActions({
    //   signIn:'auth/login' // store/modules/auth/login({commit})
    // }),
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    async resetPassword(event) {
      event.preventDefault()
      this.loading = true
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.loading = false
        return;
      }

      await window.axios.post('/reset-password', this.form)
          .then((resp) => {
            console.log(resp.status)
            console.log(resp.data)
            if(resp.status === 200) {
              // this.signIn()
              this.alert.variant = 'success'
              this.alert.msg = resp.data.message
              this.loginBtn = true
            }else {
              this.alert.variant = 'danger'
              this.alert.msg = `Invalid status code from register form response: ${resp.status}`
            }
          })
          .catch(({response}) => {
            console.error(response)
            this.alert.variant = 'danger'
            this.alert.msg = response ? Object.values(response.data.errors).join(' ') : 'General Error (or already loggedIn)'
          })
          .finally(() => {
            this.loading = false
            this.alert.show = true
          })
    }
  }
}
</script>
<style lang="scss" scoped>
  .form-group {
    padding:20px 0;
  }
</style>
