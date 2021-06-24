<template>
  <transition name="fade">
    <div :class="overlayClasses" v-if="isVisible">
      <div class="modal-close-slot">
        <slot name="top-right-close"></slot>
      </div>
      <div :class="dialogClasses">
        <div :class="contentClasses">
          <slot></slot>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
// Taken from https://stackabuse.com/how-to-create-a-confirmation-dialogue-in-vue-js/
// Modal-overlay class is in charge of the overlay color and attributes of the overlay. It can be
// changed for a custom style.
// Modal-dialog bootstrap class is in charge of handle the box size
// Modal-content bootstrap class have the attributes for a nice default modal. It can be changed.
export default {
  props: {
    overlayClasses: {
      type: String,
      default: () => {
        return "modal-overlay";
      },
    },
    dialogClasses: {
      type: String,
      default: () => {
        return "modal-dialog";
      }
    },
    contentClasses: {
      type: String,
      default: () => {
        return "modal-content";
      },
    }
  },

  data() {
    return {
      isVisible: false,
    };
  },

  methods: {
    open() {
      this.isVisible = true;
    },

    close() {
      this.isVisible = false;
    },
  },
};
</script>
<style scoped>
/* css class for the transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.modal-overlay {
  background-color: rgba(0, 0, 0, 0.5);
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

.modal-close-slot {
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>