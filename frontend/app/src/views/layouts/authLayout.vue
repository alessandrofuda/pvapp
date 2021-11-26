<template>
  <div class="auth-layout">
    <main class="auth-layout-main">
      <router-view></router-view>
    </main>
  </div>
</template>
<style lang="scss">
  .card {
    .card-header {
      margin-top: -3.13rem;
      margin-bottom: 1rem;
    }
    .card-text {
      .b-icon {
        position: absolute;
        width: 40px;
        height: auto;
        color: #aec4fc;
      }
      input,.invalid-feedback {
        width: calc(100% - 50px);
        margin-left: 50px;
      }
    }
    .card-footer {
      text-align: right;
      background:none;
    }
  }
</style>
