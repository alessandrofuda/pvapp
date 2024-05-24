<template>
  <b-form @submit="login">
    <b-form-group id="email-group" label-for="email">
      <b-icon-envelope-fill></b-icon-envelope-fill>
      <b-form-input
          id="email"
          v-model="$v.form.email.$model"
          :state="validateState('email')"
          type="email"
          placeholder="Email"
          aria-describedby="email-live-feedback"
          autofocus
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
          placeholder="Password"
          aria-describedby="password-live-feedback"
      ></b-form-input>
      <b-form-invalid-feedback id="password-live-feedback">This is a required field (min {{ form.pswMinLength }} characters).</b-form-invalid-feedback>
    </b-form-group>
    <b-button size="lg" class="w-100" type="submit" variant="primary">Login</b-button>
    <b-alert class="my-5 py-3" :show="alert.show" :variant="alert.variant"  dismissible>
      {{ alert.msg }}
    </b-alert>
    <b-overlay :show="loading" no-wrap></b-overlay>
  </b-form>
</template>
<script>
  import { validationMixin } from "vuelidate"
  import { required, minLength, email } from "vuelidate/lib/validators"
  import { mapActions } from "vuex"

  export default {
    name: 'LoginForm',
    mixins: [validationMixin],
    data() {
      return {
        form: {
          email: null,
          password: null,
          pswMinLength: 3
        },
        alert: {
          show: false,
          variant: null,
          msg: ''
        },
        loading: false
      }
    },
    validations() {
      return {
        form: {
          email: {
            required, email
          },
          password: {
            required, minLength: minLength(this.form.pswMinLength)
          }
        }
      }
    },
    methods: {
      ...mapActions({
        signIn:'auth/login' // store/modules/auth/login({commit})
      }),
      validateState(name) {
        const { $dirty, $error } = this.$v.form[name];
        return $dirty ? !$error : null;
      },
      async login(event) {
        event.preventDefault()
        this.loading = true
        this.$v.form.$touch();
        if (this.$v.form.$anyError) {
          this.loading = false
          return;
        }

        await window.axios.get('/sanctum/csrf-cookie')
        await window.axios.post('/login', this.form)
            .then(({status}) => {
              if(status === 200) {
                this.signIn()
              }else {
                this.alert.variant = 'danger'
                this.alert.msg = `Invalid status code on login: ${status}`
              }
            })
            .catch(({response}) => {
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
