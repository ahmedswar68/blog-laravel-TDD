<template>

  <div :id="'reply-'+id" class="card" :class="isBest ? 'bg-success': ''">
    <div class="card-header">
      <div class="level">
        <h5 class="flex">
          <a :href="'/profiles/'+reply.owner.name" v-text="reply.owner.name">

          </a>
          said since <span v-text="ago"></span>

        </h5>
        <!--@if(Auth::check())-->
        <div v-if="signedIn">
          <Favorite :reply="reply"></Favorite>
        </div>
        <!--@endif-->
      </div>
    </div>
    <div class="card-body">
      <div v-if="editing">
        <form @submit="update">
          <div class="form-control">
            <textarea class="form-control" v-model="body" required></textarea>
          </div>
          <button class="btn btn-xs btn-primary">Update</button>
          <button class="btn btn-xs btn-link" @click="editing=false" type="button">Cancel</button>
        </form>
      </div>
      <div v-else v-html="body"></div>
    </div>
    <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
      <div v-if="authorize('owns', reply)">
        <button class="btn btn-xs btn-primary mr-1" @click="editing = true">Edit</button>
        <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
      </div>

      <button class="btn btn-xs btn-default ml-a" @click="markBestReply" v-if="authorize('owns', reply.thread)">Best Reply?
      </button>

    </div>
  </div>

</template>
<script>
  import Favorite from './FavoriteComponent.vue';
  import moment from 'moment';

  export default {
    name: "ReplyComponent",
    props: ['reply'],
    components: {Favorite},
    data() {
      return {
        editing: false,
        id: this.reply.id,
        body: this.reply.body,
        isBest: this.reply.isBest
      }
    },
    computed: {
      ago() {
        return moment(this.reply.created_at).fromNow();
      }
    },
    created() {
      window.events.$on('best-reply-selected', id => {
        this.isBest = (id === this.id);
      });
    },
    methods: {
      update() {
        axios.patch('/replies/' + this.reply.id, {
          body: this.body
        }).catch(error => {
          flash(error.response.data, 'danger');
        });
        this.editing = false;
        flash('updated');
      },
      destroy() {
        axios.delete('/replies/' + this.id);
        this.$emit('deleted', this.id);
        // $(this.$el).fadeOut(300, () => {
        //   flash('you reply has been deleted.');
        // });
      },
      markBestReply() {
        axios.post('/replies/' + this.id + '/best');
        window.events.$emit('best-reply-selected', this.id);
      }
    }
  }
</script>

<style scoped>

</style>