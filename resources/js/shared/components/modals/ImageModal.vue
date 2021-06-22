<template>
  <modal
    name="image-modal"
    transition="pop-out"
    :adaptive="true"
    class="modal-container"
    classes="modal-image-dialog"
    :focus-trap="true"
    height="auto"
  >
    <div slot="top-right">
      <span class="modal-image-close" @click="$modal.hide('image-modal')">&times;</span>
    </div>
    <div class="modal-image-content">
      <img :src="src" class="modal-image">
    </div>
  </modal>
</template>
<script>
export default {
  props: ["src", "caption", "alt"],
};
</script>
<style>
.modal-image-content {
  height: 100%;
  max-height: 90vh;
  width: 100%;
}
.modal-image {
  display: block;
  margin: auto;
  object-fit: contain;
  height: 100%;
  width: 100%;
}
.modal-container {
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.8);
}
.modal-image-dialog {
  background-color: transparent;
  position: absolute;
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