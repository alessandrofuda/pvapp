<template>
  <div class="auth-layout">
    <main class="auth-layout-main">
      <slot/>
    </main>
  </div>
</template>
<style lang="scss">
  .card {
    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
    a {
      text-decoration: none;
      color: #8ec5fc;
    }
    .card-header {
      border: none;
      color: #ffffff;
      background-image: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%);
      padding: 1rem;
      margin-top: -3.13rem;
      margin-bottom: 1rem;
      text-align: center;
      box-shadow: 0 5px 11px 0 rgba(0,0,0,0.18),0 4px 15px 0 rgba(0,0,0,0.15);
      border-radius: .25rem !important;
      .b-icon {
        margin-right: 10px;
      }
    }
    .card-text {
      .b-icon {
        position: absolute;
        width: 40px;
        height: auto;
        color: #c9b3de;
      }
      input,.invalid-feedback {
        width: calc(100% - 50px);
        margin-left: 50px;
        &.form-control::placeholder {
          color: #b0b3b5;
        }
      }
    }
    .card-footer {
      text-align: right;
      background:none;
    }
  }
</style>
