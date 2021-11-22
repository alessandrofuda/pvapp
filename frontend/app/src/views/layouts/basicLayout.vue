<template>
  <div class="basic-layout">
    <nav id="nav" class="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>
    </nav>
    <main class="basic-layout-main">
      <slot/>
    </main>
    <footer class="basic-layout-footer">
      &copy; Awesome Company
    </footer>
  </div>
</template>
