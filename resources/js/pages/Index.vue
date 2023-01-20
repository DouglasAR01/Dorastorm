<template>
  <div class="d-flex flex-column min-vh-100">
    <the-navbar></the-navbar>
    <main>
      <router-view></router-view>
    </main>
    <the-footer></the-footer>
  </div>
</template>
<script>
import TheNavbar from "../components/navbar/TheNavbar";
import TheFooter from "../components/TheFooter";
export default {
  components: { TheNavbar, TheFooter },
};
</script>
