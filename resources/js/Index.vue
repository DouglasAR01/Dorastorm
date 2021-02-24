<template>
  <div>
    <navbar></navbar>
    <div class="container mt-4 mb-4 pr-4 pl-4">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
import Navbar from "./components/Navbar";
export default {
  components: { Navbar },
  
}
</script>
