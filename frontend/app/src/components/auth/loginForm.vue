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
  </b-form>
</template>
<script>
  import { validationMixin } from "vuelidate"
  import { required, minLength, email } from "vuelidate/lib/validators"

  export default {
    name: 'LoginForm',
    mixins: [validationMixin],
    data() {
      return {
        form: {
          email: null,
          password: null,
          pswMinLength: 3
        }
      }
    },
    validations() {
      return {
        form: {
          email: {
            required,
            email
          },
          password: {
            required,
            minLength: minLength(this.form.pswMinLength)
          }
        }
      }
    },
    methods: {
      validateState(name) {
        const { $dirty, $error } = this.$v.form[name];
        return $dirty ? !$error : null;
      },
      login(event) {
          event.preventDefault()
          this.$v.form.$touch();
          if (this.$v.form.$anyError) {
            return;
          }
          window.axios.post('/login', this.form)
            .then(resp => {
                  console.log(resp)
            })
            .catch(err => {
              console.error(err)
            })
            .finally(() => this.loading = false)
      }
    }
  }
</script>
<style lang="scss" scoped>
  .form-group {
    padding:20px 0;
  }
</style>
