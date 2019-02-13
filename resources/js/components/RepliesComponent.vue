<template>
  <div>
    <div v-for="(reply,index) in items" :key="reply.id">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>
    <paginator :dataSet="dataSet" @changed="fetch"></paginator>
    <NewReplyComponent @created="add"></NewReplyComponent>
  </div>
</template>

<script>
  import Reply from './ReplyComponent';
  import NewReplyComponent from './NewReplyComponent';
  import collection from '../mixins/collection';

  export default {
    name: 'replies',
    components: {Reply, NewReplyComponent},
    mixins: [collection],
    data() {
      return {
        dataSet: false,
        items: [],
      }
    },
    created() {
      this.fetch();
    },
    methods: {
      fetch(page) {
        axios.get(this.url(page))
          .then(this.refresh);
      },
      refresh({data}) {
        this.dataSet = data;
        this.items = data.data
      },
      url(page) {
        if (!page) {
          let query = location.search.match(/page=(\d+)/);
          page = query ? query[1] : 1;
        }
        return location.pathname + '/replies?page=' + page;
      },

    }
  }
</script>