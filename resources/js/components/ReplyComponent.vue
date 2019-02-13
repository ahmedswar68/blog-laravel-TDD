<template>

  <div :id="'reply-'+id" class="card card-default">
    <div class="card-header">
      <div class="level">
        <h5 class="flex">
          <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name">

          </a>
          said since <span v-text="ago"></span>

        </h5>
        <!--@if(Auth::check())-->
        <div v-if="signedIn">
          <Favorite :reply="data"></Favorite>
        </div>
        <!--@endif-->
      </div>
    </div>
    <div class="card-body">
      <div v-if="editing">
        <div class="form-control">
          <textarea class="form-control" v-model="body"></textarea>
        </div>
        <button class="btn btn-xs btn-primary" @click="update">Update</button>
        <button class="btn btn-xs btn-link" @click="editing=false">Cancel</button>
      </div>
      <div v-else v-text="body"></div>
    </div>
    <div class="card-footer level" v-if="canUpdate">
      <button class="btn btn-dark btn-xs mr-1" @click="editing=true">Edit</button>
      <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>

    </div>
  </div>

</template>
<script>
  import Favorite from './FavoriteComponent.vue';
  import moment from 'moment';

  export default {
    name: "ReplyComponent",
    props: ['data'],
    components: {Favorite},
    data() {
      return {
        editing: false,
        id: this.data.id,
        body: this.data.body
      }
    },
    computed: {
      ago() {
        return moment(this.data.created_at).fromNow();
      },
      signedIn() {
        return window.App.signedIn;
      },
      canUpdate() {
        return this.authorize(user => this.data.user_id == user.id);
      }
    },
    methods: {
      update() {
        axios.patch('/replies/' + this.data.id, {
          body: this.body
        });
        this.editing = false;
        flash('updated');
      },
      destroy() {
        axios.delete('/replies/' + this.data.id);
        this.$emit('deleted', this.data.id);
        // $(this.$el).fadeOut(300, () => {
        //   flash('you reply has been deleted.');
        // });
      }
    }
  }
</script>

<style scoped>

</style>