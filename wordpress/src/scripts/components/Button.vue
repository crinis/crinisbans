<template>
  <a :href="href"
     :target="target"
     :title="title"
     class="has-text-left button is-primary is-info"
     @click="emit">
    <slot></slot>
    <span class="fa"
          :class="icon">
  			<span class="sc-content">
  				{{ title }}
  			</span>
    </span>
  </a>
</template>

<script>

export default {
  props: ['href', 'title', 'icon', 'target', 'preventDefault'],
  data () {
    return {}
  },
  methods: {
    emit: function (event) {
      if (this.preventDefault) event.preventDefault()
      this.$emit('click')
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
