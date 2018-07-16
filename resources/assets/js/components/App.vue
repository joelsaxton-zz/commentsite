<template>
  <div id="myApplication">
    <h1>Game of Thrones Comment Site</h1>
    <ul>
    <li v-for="(comment, index) of comments" :key="index">
    {{ comment.user.first_name + ' ' + comment.user.last_name}}: {{ comment.comment_text }}
    </li>
    <li v-for="(subcomment, index) of comment.replies" :key="index">
        {{ subcomment.user.first_name + ' ' + subcomment.user.last_name}}: {{ subcomment.comment_text }}
        </li>
   </ul>
   <button @click="loadData()">Load Data</button>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'app',
  data: () => ({
    comments: []
  }),
  mounted() {
    //this.loadData()
  },
  methods: {
    loadData () {
        axios.get('api.json').then(response => {
            console.log(response);
            const {comments} = response.data;
            console.log(comments);
            // empty previous records
            this.comments.length = 0;
            // add new messages
            this.comments.push(...comments);
        })
    }
  }
}
</script>

<style lang="scss">
#myApplication {
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
  p { color: red; }
}
</style>