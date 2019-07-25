<template>
<div>
  <h1>Godot Asset Library</h1>
  <ul>
    <router-link :to="{ name: 'index' }">Home</router-link>
    <router-link :to="{ name: 'login' }">Login</router-link>
  </ul>
  <router-view></router-view>
</div>
</template>

<script>
export default {}
</script>
