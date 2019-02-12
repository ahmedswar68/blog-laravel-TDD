<template>
  <div>
    <div v-for="(reply,index) in items" :key="reply.id">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>
    <NewReplyComponent @created="add" :endpoint="endpoint"></NewReplyComponent>
  </div>
</template>

<script>
  import Reply from './ReplyComponent';
  import NewReplyComponent from './NewReplyComponent';

  export default {
    name: 'replies',
    components: {Reply, NewReplyComponent},
    props: ['data'],
    data() {
      return {
        items: this.data,
        endpoint: location.pathname + '/replies'
      }
    },
    methods: {
      add(reply) {
        this.items.push(reply);
        this.$emit('added');
      },
      remove(index) {
        this.items.splice(index, 1);
        this.$emit('removed');
        flash('Reply was deleted')
      }
    }
  }
</script>