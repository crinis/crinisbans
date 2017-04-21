<template>
  <div class="modal is-active">
    <div class="modal-background"
         @click="$emit('close')"></div>
    <div class="modal-content">
      <slot></slot>
    </div>
    <button class="modal-close"
            @click="$emit('close')"></button>
  </div>
</template>

<script>

export default {
  props: [],
  data () {
    return {
    }
  }
}
</script>

<style lang="scss">

</style>
