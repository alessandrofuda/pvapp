<template>
  <div class="basic-layout">
    <b-container>
      <nav id="nav" class="nav">
        <router-link to="/">Home</router-link> |
        <router-link to="/about">About</router-link>
      </nav>
      <main class="basic-layout-main">
        <router-view></router-view>
      </main>
      <footer class="basic-layout-footer">
        &copy; Awesome Company
      </footer>
    </b-container>
  </div>
</template>
