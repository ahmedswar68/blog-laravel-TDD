<template>
  <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>
  export default {
    name: "SubscribeButtonComponent",
    props: ['active'],
    // data() {
    //   return {
    //     active: this.active,
    //   }
    // },
    computed: {
      classes() {
        return ['btn', this.active ? 'btn-primary' : 'btn-outline-secondary'];
      }
    },
    methods: {
      subscribe() {
        axios[(this.active ? 'delete' : 'post')](location.pathname + '/subscriptions');
        this.active = !this.active;
      },
    }
  }
</script>

<style scoped>

</style>