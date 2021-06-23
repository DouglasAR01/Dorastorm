<template>
  <button class="btn btn-warning btn-sm" @click.prevent="$emit('confirmed')">
    <slot>Replace</slot>
  </button>
</template>