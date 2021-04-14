<script>
import ContentLoader from "./Loader";

export default {
  components: { ContentLoader },
};
</script>

<template>
  <content-loader
    :width="200"
    :height="142"
    :speed="2"
  >
    <path d="M 26.562 1 h 42 v 42 h -42 z" />
    <path d="M 74.396 1.333 h 82 v 12 h -82 z M 74.146 17 h 99 v 25 h -99 z" />
    <path d="M 26.854 50 h 42 v 42 h -42 z" />
    <path d="M 74.688 50.333 h 82 v 12 h -82 z M 74.438 66 h 99 v 25 h -99 z" />
    <path d="M 26.854 99.332 h 42 v 42 h -42 z" />
    <path
      d="M 74.688 99.666 h 82 v 12 h -82 z M 74.438 115.332 h 99 v 25 h -99 z"
    />
  </content-loader>
</template>