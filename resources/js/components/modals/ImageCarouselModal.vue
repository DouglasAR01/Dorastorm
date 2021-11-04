<template>
  <base-modal
    ref="base"
    overlay-classes="modal-image-overlay"
    dialog-classes="m-auto"
    content-classes="modal-image-dialog"
  >
    <template #top-right-close>
      <span class="modal-image-close" @click="$_ImageModal_hide()"
        >&times;</span
      >
    </template>
    <div class="modal-image-content">
      <div v-if="content" style="position: relative">
        <div class="carousel-control carousel-prev">
          <button
            class="btn text-white"
            :disabled="!hasPrevious"
            @click="active -= 1"
          >
            <i class="fas fa-chevron-left"></i>
          </button>
        </div>
        <div class="carousel-control carousel-next">
          <button
            class="btn text-white"
            :disabled="!hasNext"
            @click="active += 1"
          >
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
        <img
          :src="image.src"
          v-for="(image, index) in content"
          :key="index"
          class="modal-image"
          :class="[{ 'd-none': index != active }]"
        />
      </div>
    </div>
  </base-modal>
</template>
<script>
import BaseModal from "./BaseModal";
import Carousel from "../carousel/Carousel";
export default {
  components: {
    BaseModal,
    Carousel,
  },
  data() {
    return {
      content: null,
      active: 0,
    };
  },
  methods: {
    show(content) {
      this.content = content;
      this.active = 0;
      this.$refs.base.open();
    },
    $_ImageModal_hide() {
      this.$refs.base.close();
    },
  },
  computed: {
    hasNext() {
      if (this.content && this.active < this.content.length - 1) {
        return true;
      }
      return false;
    },
    hasPrevious() {
      if (this.content && this.active > 0) {
        return true;
      }
      return false;
    },
  },
};
</script>
<style scoped>
.modal-image-content {
  width: 80%;
  margin: auto;
}
.modal-image {
  display: block;
  margin: auto;
  object-fit: contain;
  max-height: 100vh;
  width: 100%;
}
.modal-image-overlay {
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.85);
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  z-index: 998;
}
.modal-image-dialog {
  background-color: transparent;
  margin: auto;
}
.modal-image-close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}
.modal-image-close:hover,
.modal-image-close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}
.carousel-control {
  position: absolute;
  top: 50%;
  transform: translate(0, -50%);
  background-color: rgba(0, 0, 0, 0.85);
}
.carousel-prev {
  left: 10px;
}
.carousel-next {
  right: 10px;
}
</style>