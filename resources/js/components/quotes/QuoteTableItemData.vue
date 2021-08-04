<template>
  <div class="p-2">
    <div class="row">
      <div class="col-md-6">
        <strong>{{ $t("message.email") }}</strong>
        {{ quote.email }}
      </div>
      <div class="col-md-6">
        <strong>{{ $t("modules.quotes.phone") }}:</strong>
        {{ quote.phone }}
      </div>
    </div>
    <strong>{{$t("message.message")}}:</strong>
    <p>
      {{ quote.content }}
    </p>
  </div>
</template>
<script>
export default {
  props: {
    quote: {
      type: Object,
      required: true,
    },
  },
};
</script>