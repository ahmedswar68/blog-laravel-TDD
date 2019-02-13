<template>
  <div>
    <div v-if="signedIn">
      <div class="form-group">
      <textarea
        name="body"
        id="body"
        class="form-control"
        v-model="body"
        placeholder="Say something"
        required
      ></textarea>
      </div>
      <button type="submit" class="btn btn-primary" @click="addReply"> Post</button>
    </div>
    <!--</form>-->
    <!--@else-->
    <p class="text-center" v-else>
      Please <a href="/login">sign in</a> to participate in this discussion
    </p>
    <!--@endif-->
  </div>
</template>

<script>
  export default {
    name: "NewReplyComponent",
    data() {
      return {
        body: '',
      }
    },
    computed: {
      signedIn() {
        return window.App.signedIn;
      }
    },
    methods: {
      addReply() {
        axios.post(location.pathname + '/replies', {body: this.body})
          .then(({data}) => {
            this.body = '';
            // flash('your reply has been posted');
            this.$emit('created', data)
          });
      }
    }
  }
</script>

<style scoped>

</style>