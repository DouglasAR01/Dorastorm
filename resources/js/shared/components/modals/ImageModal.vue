<template>
  <base-modal ref="base" overlay-classes="modal-image-overlay" dialog-classes="m-auto" content-classes="modal-image-dialog">
    <template v-slot:top-right-close>
      <span class="modal-image-close" @click="$_ImageModal_hide()">&times;</span>
    </template>
    <div class="modal-image-content">
      <img :src="src" class="modal-image">
    </div>
  </base-modal>
</template>
<script>
import BaseModal from "./BaseModal";
export default {
  components: {
    BaseModal
  },
  data () {
    return {
      src: null,
      caption: null,
      alt: null
    }
  },
  methods: {
    show(opts = {}){
      this.src = opts.src;
      if (opts.caption){
        this.caption = opts.caption;
      }
      if (opts.alt){
        this.alt = opts.alt;
      }      
      this.$refs.base.open();
    },
    $_ImageModal_hide(){
      this.$refs.base.close();
    }
  }
}
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
  height: 100%;
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
</style>