<template>
  <div class="d-flex flex-column min-vh-100">
    <navbar></navbar>
    <div class="container mt-4 mb-4 pr-4 pl-4">
      <router-view></router-view>
    </div>
    <footer-component></footer-component>
  </div>
</template>
<script>
import Navbar from "./components/Navbar";
import FooterComponent from "./components/Footer";
export default {
  components: { Navbar, FooterComponent },
};
</script>
