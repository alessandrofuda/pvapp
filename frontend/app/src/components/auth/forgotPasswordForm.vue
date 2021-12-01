<template>
  <b-form @submit="sendEmail">
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
    <b-button size="lg" class="w-100" type="submit" variant="primary">Send</b-button>
    <b-alert class="my-5 py-3" :show="alert.show" :variant="alert.variant"  dismissible>
      {{ alert.msg }}
    </b-alert>
    <b-overlay :show="loading" no-wrap></b-overlay>
  </b-form>
</template>
<script>
import { validationMixin } from "vuelidate"
import { required, email } from "vuelidate/lib/validators"

export default {
  name: 'ForgotPasswordForm',
  mixins: [validationMixin],
  data() {
    return {
      form: {
        email: null
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
          required,
          email
        }
      }
    }
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    async sendEmail(event) {
      event.preventDefault()
      this.loading = true
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.loading = false
        return;
      }

      await window.axios.post('/forgot-password', this.form)
          .then(({status}) => {
            if(status === 200) {
              this.alert.variant = 'success'
              this.alert.msg = 'Mail sent. Open it and click the link to reset the password.'
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
