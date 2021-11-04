<template>
  <div class="container wp">
    <h1 class="display-3">Dorastorm</h1>
    <p class="lead">Dorastorm has been successfully installed.
      If you have already done the database seeding, you should be able to log in with a user.
    </p>
    <p>In this page you should place your landingpage, pitch elevator, etc.</p>
    <div class="p-2 bg-light">
      <quote-form></quote-form>
    </div>
  </div>
</template>
<script>
import QuoteForm from "../components/quotes/QuoteForm.vue";
export default {
  components: {
    QuoteForm
  },
  metaInfo: {
    meta: [
      {name:"description", content:"Landing page description"}
    ]
  }
}
</script>